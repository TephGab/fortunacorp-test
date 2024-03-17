<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\TransactionActivit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ApprovalOrDisapprovalTransactionHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $approvedDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction, $approvedDate)
    {
        $this->transaction = $transaction;
        $this->approvedDate = $approvedDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transaction = $this->transaction;
        $approvedDate = $this->approvedDate;

        $agent = $transaction->user;
        $operator = $transaction->operator;

        try{
            DB::beginTransaction();
            
            $transaction_history = new TransactionActivit;
            $transaction_history->sender_amount = $transaction->sender_amount;
            $transaction_history->receiver_amount = $transaction->receiver_amount;
            $transaction_history->qr_code = $transaction->qr_code;
            $transaction_history->rate = $transaction->rate;
            $transaction_history->fortuna_fee = $transaction->fortuna_fee;
            $transaction_history->p_to_p_fee = $transaction->p_to_p_fee;
            $transaction_history->status = $transaction->status;
            $transaction_history->type = $transaction->type;
            $transaction_history->operation = $transaction->operation;
            $transaction_history->sender = $transaction->sender;
            $transaction_history->receiver = $transaction->receiver;
            $transaction_history->user_id = $transaction->user_id;
            $transaction_history->client_id = $transaction->client_id;
            $transaction_history->operator_id = $transaction->operator_id;
            $transaction_history->transaction_id = $transaction->id;
            $transaction_history->approved_by = $transaction->approved_by;
            $transaction_history->approved_date = $approvedDate;
            $transaction_history->disapproved_date = $transaction->status === 'disapproved' ? $approvedDate : null;
            $transaction_history->current_agent_balance = $agent->user_account->profits;
            $transaction_history->new_agent_balance = $agent->user_account->profits;
            $transaction_history->current_agent_investment = $agent->user_account->investments;
            $transaction_history->new_agent_investment = $agent->user_account->investments;
            $transaction_history->current_operator_balance = $operator->user_account->profits;
            $transaction_history->new_operator_balance = $operator->user_account->profits;
            $transaction_history->step = $transaction->status;

            $transaction_history->save();
            
            DB::commit();
        } catch (QueryException $exception) {
                DB::rollBack();
                Log::error('Error durring durring transaction history registration: ' . $exception->getMessage());
                return response()->json([
                    'message' => 'Error occured durring transaction history registration!',
                    'errors' => [
                        'throw_execetion' => [
                            'Error occured durring transaction history registration.'
                        ]
                    ]
                ], 400);
        }
    }
}
