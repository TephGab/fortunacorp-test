<?php

namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Services\Transactions\TransactionStoreService;

class TransactionCheckInvervalService
{
    protected $transactionStoreService;

    public function __construct(TransactionStoreService $transactionStoreService)
    {
        $this->transactionStoreService = $transactionStoreService;
    }

    public function checkIntervalTransaction($request, $checkSender, $checkReceiver, $user, $withdrawal, $operator)
    {
        return $this->transactionStoreService->storeTransaction($request, $checkSender, $checkReceiver, $user, $withdrawal, $operator);
        // $lastTransaction = Transaction::where('user_id', $user->id)->latest()->first();

        // if ($lastTransaction) {
        //     $currentTime = Carbon::now();
        //     $lastTransactionTime = Carbon::parse($lastTransaction->created_at);
        //     $differenceInSeconds = $currentTime->diffInSeconds($lastTransactionTime);

        //     switch (true) {
        //         case $differenceInSeconds < 10:
        //             return response()->json([
        //                 'message' => 'Transaction interval.',
        //                 'errors' => [
        //                     'transaction_interval' => [
        //                         'Please wait at least 10 seconds between transactions.'
        //                     ]
        //                 ]
        //             ], 400);
        //         break;

        //         default:
        //             return $this->transactionStoreService->storeTransaction($request, $checkSender, $checkReceiver, $user, $withdrawal, $operator);
        //         break;
        //     }
        // } else {
        //     return $this->transactionStoreService->storeTransaction($request, $checkSender, $checkReceiver, $user, $withdrawal, $operator);
        // }
    }
}