<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Models\TransactionActivit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class StoreTransactionHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $transaction;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction, $user)
    {
        $this->transaction = $transaction;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            DB::beginTransaction();
            // Set Transaction History
            $transaction_history = new TransactionActivit;
            $transaction_history->sender_amount =  $this->transaction->sender_amount;
            $transaction_history->receiver_amount = $this->transaction->receiver_amount;
            $transaction_history->qr_code = $this->transaction->qr_code;
            $transaction_history->rate = $this->transaction->rate;
            $transaction_history->fortuna_fee = $this->transaction->fortuna_fee;
            $transaction_history->p_to_p_fee = $this->transaction->p_to_p_fee;
            $transaction_history->status = $this->transaction->status;
            $transaction_history->type = $this->transaction->type;
            $transaction_history->operation = $this->transaction->operation;
            $transaction_history->sender =  $this->transaction->sender;
            $transaction_history->receiver = $this->transaction->receiver;
            $transaction_history->user_id = $this->transaction->user_id;
            $transaction_history->client_id = $this->transaction->client_id;
            $transaction_history->operator_id = $this->transaction->operator_id;
            $transaction_history->transaction_id = $this->transaction->id;

            $transaction_history->step = 'initialization';
            $transaction_history->save();
            // Set Transaction History end
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
