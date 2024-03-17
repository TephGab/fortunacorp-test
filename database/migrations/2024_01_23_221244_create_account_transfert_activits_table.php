<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransfertActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transfert_activits', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2);
            $table->foreignId('user_id')->constrained();
            $table->integer('account_sender_id');
            $table->string('account_sender_name'); 
            $table->string('account_sender_phone'); 
            $table->integer('account_receiver_id');
            $table->string('account_receiver_name'); 
            $table->string('account_receiver_phone');
            $table->foreignId('account_transfert_id')->constrained();

             // 
             $table->double('current_user_balance', 15, 2)->nullable();
             $table->double('new_user_balance', 15, 2)->nullable();
             $table->double('current_system_debt', 15, 2)->nullable();
             $table->double('new_system_debt', 15, 2)->nullable();
             $table->double('current_sender_account_balance', 15, 2)->nullable();
             $table->double('new_sender_account_balance', 15, 2)->nullable();
             $table->double('current_receiver_account_balance', 15, 2)->nullable();
             $table->double('new_receiver_account_balance', 15, 2)->nullable();
             $table->double('current_system_fund', 15, 2)->nullable();
             $table->double('new_system_fund', 15, 2)->nullable();
             $table->string('step');
             // 
            $table->timestamps();
            $table->boolean('countable')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_transfert_activits');
    }
}
