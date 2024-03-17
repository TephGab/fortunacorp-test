<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\TransactionComment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StoreTransactionCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $commentCategory;
    protected $commentContent;
    protected $userId;
    protected $transactionId;
    protected $timing;

    /**
     * Create a new job instance.
     *
     * @param string $commentCategory
     * @param string $commentContent
     * @param int $userId
     * @param int $transactionId
     * @param string $timing
     */
    public function __construct($commentCategory, $commentContent, $userId, $transactionId, $timing)
    {
        $this->commentCategory = $commentCategory;
        $this->commentContent = $commentContent;
        $this->userId = $userId;
        $this->transactionId = $transactionId;
        $this->timing = $timing;
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
            $comment = new TransactionComment;
            $comment->category = $this->commentCategory;
            $comment->content = $this->commentContent;
            $comment->user_id = $this->userId;
            $comment->transaction_id = $this->transactionId;
            $comment->timing= $this->timing;
            $comment->save();
            DB::commit();
        }catch (QueryException $exception) {
                DB::rollBack();
                Log::error('Error durring durring transaction comment registration: ' . $exception->getMessage());
                return response()->json([
                    'message' => 'Error occured durring transaction comment registration!',
                    'errors' => [
                        'throw_execetion' => [
                            'Error occured durring transaction comment registration.'
                        ]
                    ]
                ], 400);
        }
    }
}
