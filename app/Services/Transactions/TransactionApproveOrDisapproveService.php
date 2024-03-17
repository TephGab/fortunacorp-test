<?php

namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Events\TransactionEvent;
use Illuminate\Support\Facades\DB;
use App\Jobs\TransactionCommentJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Jobs\ApprovalOrDisapprovalTransactionHistoryJob;

class TransactionApproveOrDisapproveService
{
    public function approveOrDisapproveStatus($request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);
    
        if ($transaction->status == 'completed') {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'completed_transaction' => [
                        'This transaction has already been completed!'
                    ]
                ]
            ], 400);
        }
    
        $laravelTimezone = config('app.timezone');
        $approvedDate = Carbon::now($laravelTimezone);
    
        try{
            DB::beginTransaction();
            $transaction->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'approved_date' => $approvedDate,
        ]);
            DB::commit();
        } catch (QueryException $exception) {
            DB::rollBack();
            Log::error('Error durring durring transaction update: ' . $exception->getMessage());
            return response()->json([
                'message' => 'Error occured durring transaction update!',
                'errors' => [
                    'throw_execetion' => [
                        'Error occured durring transaction update.'
                    ]
                ]
            ], 400);
        }
    
        if ($request->comment) {
            $commentCategory = $request->comment_category;
            $commentContent = $request->comment;
            $userId = Auth::id();
            $transactionId = $request->transaction_id;
            $timing = $request->status;
        
            TransactionCommentJob::dispatch($commentCategory, $commentContent, $userId, $transactionId, $timing);
        }

        ApprovalOrDisapprovalTransactionHistoryJob::dispatch($transaction, $approvedDate);
    
        event(new TransactionEvent($transaction, Auth::user()));
        
        return Transaction::with(['client'])->orderBy('created_at', 'DESC')->paginate(50);
    }    
}