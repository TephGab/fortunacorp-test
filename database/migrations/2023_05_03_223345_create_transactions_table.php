<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->double('sender_amount', 15, 2)->nullable();
            $table->double('receiver_amount', 15, 2)->nullable();
            $table->string('qr_code')->default(false)->nullable();
            $table->double('rate', 15, 2)->nullable();
            $table->double('fortuna_fee', 15, 2)->nullable();
            $table->double('p_to_p_fee', 15, 2)->nullable();
            $table->set('status', ['pending', 'approved', 'disapproved', 'completed', 'partially_completed']);
            $table->set('type', ['moncash', 'natcash', 'local']);
            $table->set('operation', ['transfert', 'reception', 'transfert_si', 'reception_si']);
            $table->integer('sender')->nullable();
            $table->integer('receiver')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('completed_by')->nullable();
            $table->integer('account_id')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('client_id');
            $table->text('comment')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
            $table->integer('operator_id')->nullable();
            $table->boolean('viewed)')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
