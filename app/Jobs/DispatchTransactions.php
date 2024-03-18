<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class DispatchTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Retrieve all pending transactions for the current user
        $transactions = Transaction::where('operator_id', $this->userId)->where('status', 'pending')->where('operation', 'transfert_si')->get();

        if($transactions){
            foreach ($transactions as $transaction) { 
                //Retreive Operator
                $operator = User::leftJoin('transactions', function ($join) {
                    $join->on('users.id', '=', 'transactions.operator_id')
                    ->where('transactions.operator_id', '!=', null);
                })->select('users.id')
                    ->where('online_status', '=', 'online')
                    ->orWhere(function ($query) {
                    $query->groupBy('users.id')->havingRaw('COUNT(transactions.id) >= 0');
                })->whereHas('roles', function ($query) {
                    $query->where('name', 'operator');
                })->orderByRaw('(SELECT COUNT(transactions.id) FROM transactions WHERE transactions.operator_id = users.id AND transactions.status = "pending") ASC')->first();
                
                if($operator){
                    $transaction->update(['operator_id' => $operator->id]);
                }else{
                    $transaction->update(['operator_id' => 1]);
                }
            }
        }


    }
}
