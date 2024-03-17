<?php

namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\SendMoney;
use App\Models\Transaction;
use App\Events\TransactionEvent;
use Illuminate\Support\Facades\DB;
use App\Events\NewTransactionEvent;
// use Illuminate\Support\Facades\Log;
use App\Jobs\TransactionCommentJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Jobs\StoreTransactionHistoryJob;
use App\Http\Requests\TransactionFormRequest;
// use Illuminate\Support\Facades\Response;

class TransactionStoreService
{
    public function storeTransaction(TransactionFormRequest $request)
    {
        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        //Select Operator
        $operator = $this->selectOperator($request);

        //$user = Auth::user()->with('user_account')->first();

        // ***
        //Check sender and receiver
        $checkSender = Client::where('phone', $request->sender_phone)->first();
        $checkReceiver = Client::where('phone', $request->receiver_phone)->first();
        $user = User::with(['user_account'])->findOrFail(Auth::id());
        $withdrawal = SendMoney::where('receiver_id', Auth::id())->where('status', 'pending')->sum('amount');
        // ***
    
        try {
            // Begin transaction
            DB::beginTransaction();
    
            // Initialize transaction
            $transaction = new Transaction;
    
            // Set operator id
            $transaction->operator_id = optional($operator)->id ?: 1;
    
            // Validate sender amount
            if ($request->operation == 'transfert_si' && $request->sender_amount <= $user->user_account->investments) {
                
                $agent_pending_transac = Transaction::where(function ($query) {
                    $query->whereIn('status', ['pending', 'approved', 'disapproved'])
                        ->where('user_id', Auth::id());
                })->sum('sender_amount');
    
                if ($agent_pending_transac + $withdrawal + $request->sender_amount > $user->user_account->investments) {
                    // Rollback transaction
                    DB::rollBack();
                    
                    return response()->json([
                        'message' => 'Insufficient recharge amount.',
                        'errors' => [
                            'user_debt_not_allowed_pending_withdraw' => ['Transaction blocked!']
                        ]
                    ], 400);
                }
            } elseif ($request->operation == 'transfert_si') {
                // Rollback transaction
                DB::rollBack();
                
                return response()->json([
                    'message' => 'Insufficient recharge amount.',
                    'errors' => [
                        'user_debt_not_allowed' => ['Transaction blocked!']
                    ]
                ], 400);
            }
    
            // Set client IDs
            $transaction->client_id = $checkSender ? $checkSender->id : $this->createNewClient($request->sender_name, $request->sender_phone)->id;
            $transaction->sender = $transaction->client_id;
            $transaction->receiver = $checkReceiver ? $checkReceiver->id : $this->createNewClient($request->receiver_name, $request->receiver_phone)->id;
    
            // Set transaction details
            $transaction->fill($request->except(['sender_name', 'sender_phone', 'receiver_name', 'receiver_phone']));
            $transaction->user_id = Auth::id();
            $transaction->status = 'pending';
            $transaction->save();
    
            // Dispatch jobs
            if ($request->comment) {
                $commentCategory = $request->comment_category;
                $commentContent = $request->comment;
                $userId = Auth::id();
                $transactionId = $transaction->id;
                $timing = $transaction->status;
            
                TransactionCommentJob::dispatch($commentCategory, $commentContent, $userId, $transactionId, $timing);
            }

            StoreTransactionHistoryJob::dispatch($transaction, $user);
    
            // Commit transaction
            DB::commit();
    
            // Create events
            event(new TransactionEvent($transaction, $user));
            event(new NewTransactionEvent($transaction, $user, $operator ?: User::findOrFail(1)));
    
            return response()->json(['message' => 'Transaction registration process initiated'], 201);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            return response()->json([
                'message' => 'Transaction registration failed. ' . $e->getMessage(),
                'errors' => [
                    'transaction_registration_failed' => [
                        'Transaction registration failed'
                    ]
                ]
            ], 500);
        }
    }

    private function selectOperator($request)
    {
        if ($request->operation == 'transfert_si' || $request->operation == 'transfert') {
            return User::with(['user_account'])
                ->where('online_status', '=', 'online')
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'operator');
                })
                ->leftJoin('transactions', function ($join) {
                    $join->on('users.id', '=', 'transactions.operator_id')
                        ->whereNotNull('transactions.operator_id');
                })
                ->select('users.id')
                ->groupBy('users.id')
                ->havingRaw('COUNT(transactions.id) >= 0')
                ->orderByRaw('(SELECT COUNT(transactions.id) FROM transactions WHERE transactions.operator_id = users.id AND transactions.status = "pending") ASC')
                ->first();
        } elseif ($request->operation == 'reception_si' || $request->operation == 'reception') {
            return User::whereHas('accounts', function ($query) use ($request) {
                $query->where('phone', $request->sender_phone);
            })->first();
        }

        return null;
    }
    
    private function createNewClient($name, $phone)
    {
        $client = new Client;
        $client->name = $name;
        $client->phone = $phone;
        $client->save();
        return $client;
    }
}