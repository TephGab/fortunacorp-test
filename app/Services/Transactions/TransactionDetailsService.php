<?php

namespace App\Services\Transactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionDetailsService
{
    public function transactionDetails($request)
    {
        $transaction = Transaction::with([
            'user', 
            'client',
            'sender',
            'receiver',
            'operator',
            'account',
            'transaction_activits.user',
            'transaction_activits.sender',
            'transaction_activits.receiver',
            'transaction_activits.client',
            'transaction_activits.operator',
            'transaction_activits.account',
        ])->where('id', $request->transaction_id)->first();        
        
    
        return response()->json($transaction);
    }
}