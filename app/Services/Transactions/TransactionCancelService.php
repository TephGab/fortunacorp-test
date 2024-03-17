<?php

namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use App\Events\TransactionEvent;
use App\Models\CancelTransaction;
use App\Models\TransactionActivit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class TransactionCancelService
{
    public function cancelTransaction($transactionId)
    {
        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));
        
        $transaction = Transaction::find($transactionId);
        $user = User::with(['user_account'])->findOrFail($transaction->user_id);
        $operator = User::with(['user_account'])->findOrFail($transaction->operator_id);

        if ($transaction && $transaction->status === 'pending' && !$transaction->viewed) {
            try {
                $transaction_cancel = new CancelTransaction;
                $transaction_cancel->sender_amount =  $transaction->sender_amount;
                $transaction_cancel->receiver_amount = $transaction->receiver_amount;
                $transaction_cancel->qr_code = $transaction->qr_code;
                $transaction_cancel->rate = $transaction->rate;
                $transaction_cancel->fortuna_fee = $transaction->fortuna_fee;
                $transaction_cancel->p_to_p_fee = $transaction->p_to_p_fee;
                $transaction_cancel->status = $transaction->status;
                $transaction_cancel->type = $transaction->type;
                $transaction_cancel->operation = $transaction->operation;
                $transaction_cancel->sender =  $transaction->sender;
                $transaction_cancel->receiver = $transaction->receiver;
                $transaction_cancel->user_id = $transaction->user_id;
                $transaction_cancel->client_id = $transaction->client_id;
                $transaction_cancel->operator_id = $transaction->operator_id;
                $transaction_cancel->transaction_id = $transaction->id;
                $transaction_cancel->save();

                //History
                $transaction_history = new TransactionActivit;
                $transaction_history->sender_amount =  $transaction->sender_amount;
                $transaction_history->receiver_amount = $transaction->receiver_amount;
                $transaction_history->qr_code = $transaction->qr_code;
                $transaction_history->rate = $transaction->rate;
                $transaction_history->fortuna_fee = $transaction->fortuna_fee;
                $transaction_history->p_to_p_fee = $transaction->p_to_p_fee;
                $transaction_history->status = $transaction->status;
                $transaction_history->type = $transaction->type;
                $transaction_history->operation = $transaction->operation;
                $transaction_history->sender =  $transaction->sender;
                $transaction_history->receiver = $transaction->receiver;
                $transaction_history->user_id = $transaction->user_id;
                $transaction_history->client_id = $transaction->client_id;
                $transaction_history->operator_id = $transaction->operator_id;
                $transaction_history->transaction_id = $transaction->id;
                $transaction_history->canceled_date = Carbon::now($laravelTimezone);

                $transaction_history->current_agent_balance = $user->user_account->profits;
                $transaction_history->new_agent_balance = $user->user_account->profits;
                $transaction_history->current_agent_investment = $user->user_account->investments;
                $transaction_history->new_agent_investment = $user->user_account->investments;
                $transaction_history->current_operator_balance = $operator->user_account->profits;
                $transaction_history->new_operator_balance = $operator->user_account->profits;
                // $transaction_history->current_account_balance = 0; 
                // $transaction_history->new_account_balance = 0;  
                // $transaction_history->current_system_fund = 0;
                // $transaction_history->new_system_fund =  = 0;
                $transaction_history->step = 'canceled';
                $transaction_history->save();
                // Set Transaction History end

                //Delete transaction
                $deleted = $transaction->forceDelete();

                if ($deleted) {
                    //Create Envent
                    event(new TransactionEvent($transaction, Auth::user()));
                    return Transaction::with(['client'])->orderBy('created_at', 'DESC')->paginate(50);
                } else {
                    return response()->json([
                        'message' => 'Transaction in process.',
                        'errors' => [
                            'transaction_in_process' => [
                                'Transaction in process'
                            ]
                        ]
                    ], 400);
                }
                
            } catch (QueryException $exception) {
                // Handle the exception
                Log::error('Error updating transaction: ' . $exception->getMessage());
                throw new \Exception('Unable to update informations');
            }

      
        } else {
            return response()->json([
                'message' => 'Transaction in process.',
                'errors' => [
                    'transaction_in_process' => [
                        'Transaction in process'
                    ]
                ]
            ], 400);
        }
    }
}