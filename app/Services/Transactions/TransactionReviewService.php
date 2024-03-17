<?php

namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Transaction;
use App\Events\TransactionEvent;
use App\Models\CancelTransaction;
use App\Models\TransactionActivit;
use App\Models\TransactionComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Notifications\Notification;

class TransactionReviewService
{
    public function reviewTransaction($request, $transaction, $user, $checkSender, $checkReceiver, $withdrawal)
    {
        $laravelTimezone = config('app.timezone');
        Carbon::setTestNow(Carbon::now($laravelTimezone));

        if ($transaction && $transaction->status === 'disapproved') {
            try {
                // Begin
                DB::beginTransaction();
                switch (true) {
                    case $request->operation == 'transfert_si':
                        if ($request->sender_amount <= $user->user_account->investments) {
                            // $agent_pending_transac = Transaction::where('user_id', Auth::id())->where('status', 'pending')->orWhere('status', 'approved')->orWhere('status', 'disapproved')->sum('sender_amount');
                            $agent_pending_transac = Transaction::where(function ($query) {
                                $query->where('status', 'pending')
                                    ->orWhere('status', 'approved')
                                    ->orWhere('status', 'disapproved');
                            })->where('id', '!=', $request->transaction_id)->where('user_id', Auth::id())->sum('sender_amount');

                            if (($agent_pending_transac + $request->sender_amount) > $user->user_account->investments) {
                                return response()->json([
                                    'message' => 'Insuficient replenishment.',
                                    'errors' => [
                                        'user_debt_not_allowed' => [
                                            'Transaction blocked!'
                                        ]
                                    ]
                                ], 400);
                            } elseif (($agent_pending_transac + $withdrawal + $request->sender_amount) > $user->user_account->investments) {
                                return response()->json([
                                    'message' => 'Insuficient replenishment.',
                                    'errors' => [
                                        'user_debt_not_allowed_pending_withdraw' => [
                                            'Transaction blocked!'
                                        ]
                                    ]
                                ], 400);
                            }
                            info('Passed.');
                            //$transaction->operator_id = $operator->id;
                        } elseif ($request->sender_amount > $user->user_account->investments) {
                            if ($user->dept_allowed) {
                                info('Passed ..');
                                // $transaction->operator_id = $operator->id;
                            } else {
                                // Block transaction
                                return response()->json([
                                    'message' => 'Insuficient replenishment.',
                                    'errors' => [
                                        'user_debt_not_allowed' => [
                                            'Transaction blocked!'
                                        ]
                                    ]
                                ], 400);
                            }
                        }
                        // $transaction->operator_id = $operator ? $operator->id : 1;
                        break;

                    default:
                        //
                        break;
                }


                if ($checkSender) {
                    $checkSender->update(['name' => $request->sender_name]);
                    $transaction->update(['sender' => $checkSender->id]);
                } else {
                    $sender = new Client;
                    $sender->name = $request->sender_name;
                    $sender->phone = $request->sender_phone;
                    $sender->save();

                    $transaction->update(['sender' => $sender->id]);
                }

                if ($checkReceiver) {
                    $checkReceiver->update(['name' => $request->receiver_name]);
                    $transaction->update(['client_id' => $checkReceiver->id]);
                    $transaction->update(['receiver' => $checkReceiver->id]);
                } else {
                    $receiver = new Client;
                    $receiver->name = $request->receiver_name;
                    $receiver->phone = $request->receiver_phone;
                    $receiver->save();

                    $transaction->update(['client_id' => $receiver->id]);
                    $transaction->update(['receiver' => $receiver->id]);
                }

                $transaction->update(['sender_amount' => $request->sender_amount]);
                $transaction->update(['receiver_amount' => $request->receiver_amount]);
                $transaction->update(['rate' => $request->rate]);
                $transaction->update(['qr_code' => $request->qr_code]);
                $transaction->update(['fortuna_fee' => $request->fortuna_fee]);
                $transaction->update(['user_id' => Auth::id()]);
                $transaction->update(['status' => 'pending']);
                $transaction->update(['operation' => $request->operation]);
                $transaction->update(['type' => $request->type]);

                if ($request->comment) {
                    $comment = new TransactionComment;
                    $comment->category = $request->comment_category;
                    $comment->content = $request->comment;
                    $comment->user_id = Auth::id();
                    $comment->transaction_id = $transaction->id;
                    $comment->timing = $transaction->status;
                    $comment->save();
                }

                // Set Transaction History
                $operator = User::findOrFail($transaction->operator_id);
                $agent = User::findOrFail($transaction->user_id);
                $client = Client::findOrFail($transaction->client_id);

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
                $transaction_history->resubmitted_date = Carbon::now($laravelTimezone);

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
                $transaction_history->step = 'resubmitted';
                $transaction_history->save();
                // Set Transaction History end

                //Create Envent
                event(new TransactionEvent($transaction, Auth::user()));

                //Create notification
                // $u = User::where('id', 2)->get();
                // Notification::send($u, new TransactionDone($data));
                // End
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Transaction in process.'. $e->getMessage(),
                    'errors' => [
                        'transaction_in_process' => [
                            'Transaction in process'
                        ]
                    ]
                ], 400);
            }
        }
        elseif ($transaction && $transaction->status === 'pending' && !$transaction->viewed) {
            try {
                // Begin
                DB::beginTransaction();
                switch (true) {
                    case $request->operation == 'transfert_si':
                        if ($request->sender_amount <= $user->user_account->investments) {
                            // $agent_pending_transac = Transaction::where('user_id', Auth::id())->where('status', 'pending')->orWhere('status', 'approved')->orWhere('status', 'disapproved')->sum('sender_amount');
                            $agent_pending_transac = Transaction::where(function ($query) {
                                $query->where('status', 'pending')
                                    ->orWhere('status', 'approved')
                                    ->orWhere('status', 'disapproved');
                            })->where('id', '!=', $request->transaction_id)->where('user_id', Auth::id())->sum('sender_amount');

                            if (($agent_pending_transac + $request->sender_amount) > $user->user_account->investments) {
                                return response()->json([
                                    'message' => 'Insuficient replenishment.',
                                    'errors' => [
                                        'user_debt_not_allowed' => [
                                            'Transaction blocked!'
                                        ]
                                    ]
                                ], 400);
                            } elseif (($agent_pending_transac + $withdrawal + $request->sender_amount) > $user->user_account->investments) {
                                return response()->json([
                                    'message' => 'Insuficient replenishment.',
                                    'errors' => [
                                        'user_debt_not_allowed_pending_withdraw' => [
                                            'Transaction blocked!'
                                        ]
                                    ]
                                ], 400);
                            }
                            info('Passed.');
                            //$transaction->operator_id = $operator->id;
                        } elseif ($request->sender_amount > $user->user_account->investments) {
                            if ($user->dept_allowed) {
                                info('Passed ..');
                                // $transaction->operator_id = $operator->id;
                            } else {
                                // Block transaction
                                return response()->json([
                                    'message' => 'Insuficient replenishment.',
                                    'errors' => [
                                        'user_debt_not_allowed' => [
                                            'Transaction blocked!'
                                        ]
                                    ]
                                ], 400);
                            }
                        }
                        // $transaction->operator_id = $operator ? $operator->id : 1;
                        break;

                    default:
                        //
                        break;
                }


                if ($checkSender) {
                    $checkSender->update(['name' => $request->sender_name]);
                    $transaction->update(['sender' => $checkSender->id]);
                } else {
                    $sender = new Client;
                    $sender->name = $request->sender_name;
                    $sender->phone = $request->sender_phone;
                    $sender->save();

                    $transaction->update(['sender' => $sender->id]);
                }

                if ($checkReceiver) {
                    $checkReceiver->update(['name' => $request->receiver_name]);
                    $transaction->update(['client_id' => $checkReceiver->id]);
                    $transaction->update(['receiver' => $checkReceiver->id]);
                } else {
                    $receiver = new Client;
                    $receiver->name = $request->receiver_name;
                    $receiver->phone = $request->receiver_phone;
                    $receiver->save();

                    $transaction->update(['client_id' => $receiver->id]);
                    $transaction->update(['receiver' => $receiver->id]);
                }

                $transaction->update(['sender_amount' => $request->sender_amount]);
                $transaction->update(['receiver_amount' => $request->receiver_amount]);
                $transaction->update(['rate' => $request->rate]);
                $transaction->update(['qr_code' => $request->qr_code]);
                $transaction->update(['fortuna_fee' => $request->fortuna_fee]);
                $transaction->update(['user_id' => Auth::id()]);
                $transaction->update(['status' => 'pending']);
                $transaction->update(['operation' => $request->operation]);
                $transaction->update(['type' => $request->type]);

                if ($request->comment) {
                    $comment = new TransactionComment;
                    $comment->category = $request->comment_category;
                    $comment->content = $request->comment;
                    $comment->user_id = Auth::id();
                    $comment->transaction_id = $transaction->id;
                    $comment->timing = $transaction->status;
                    $comment->save();
                }

                // Set Transaction History
                $operator = User::findOrFail($transaction->operator_id);
                $agent = User::findOrFail($transaction->user_id);
                $client = Client::findOrFail($transaction->client_id);

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
                $transaction_history->edited_date = Carbon::now($laravelTimezone);

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
                $transaction_history->step = 'edited';
                $transaction_history->save();
                // Set Transaction History end

                //Create Envent
                event(new TransactionEvent($transaction, Auth::user()));

                //Create notification
                // $u = User::where('id', 2)->get();
                // Notification::send($u, new TransactionDone($data));
                // End
                if (!$transaction->viewed) {
                    DB::commit();
                }else{
                    DB::rollBack();
                }
               
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Transaction in process.'. $e->getMessage(),
                    'errors' => [
                        'transaction_in_process' => [
                            'Transaction in process'
                        ]
                    ]
                ], 400);
            }
        }
        else {
            return response()->json([
                'message' => 'Transaction in process.',
                'errors' => [
                    'transaction_in_process' => [
                        'Transaction in process'
                    ]
                ]
            ], 400);
        }
        //Create Envent
        event(new TransactionEvent($transaction, Auth::user()));
    }
}