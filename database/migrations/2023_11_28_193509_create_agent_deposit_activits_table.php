<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentDepositActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_deposit_activits', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->string('status')->default('pending');
            $table->set('type', ['user_account', 'user_bank_account'])->default('user_account');
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');;
            $table->text('comment')->nullable();
            $table->integer('user_bank_account_id')->nullable();
            $table->integer('system_bank_account_id')->nullable();
            $table->integer('user_account_id')->nullable();
            $table->integer('envoy_id')->nullable(); 
            $table->integer('system_account_id')->nullable();
            $table->integer('sender_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->boolean('confirmed_by_receiver')->default(0)->nullable();
            $table->boolean('confirmed_by_envoy')->default(0)->nullable(); 
            $table->foreignId('user_id')->constrained();
            $table->foreignId('agent_deposit_id')->constrained();
            $table->string('operation')->nullable();
            $table->timestamp('initialization_date')->nullable();
            $table->timestamp('receiver_confirmation_date')->nullable();
            $table->timestamp('envoy_confirmation_date')->nullable();
            $table->timestamp('confirmation_date')->nullable();
             // 
            $table->double('current_agent_balance', 15, 2)->nullable();
            $table->double('new_agent_balance', 15, 2)->nullable();

            $table->double('current_agent_investment', 15, 2)->nullable();
            $table->double('new_agent_investment', 15, 2)->nullable();

            $table->double('current_agent_capital', 15, 2)->nullable();
            $table->double('new_agent_capital', 15, 2)->nullable();

            $table->double('current_agent_cash_in_hand', 15, 2)->nullable();
            $table->double('new_agent_cash_in_hand', 15, 2)->nullable();
            
            $table->double('current_envoy_balance', 15, 2)->nullable();
            $table->double('new_envoy_balance', 15, 2)->nullable();
            $table->double('current_envoy_debt', 15, 2)->nullable();
            $table->double('new_envoy_debt', 15, 2)->nullable();
            $table->double('current_system_fund', 15, 2)->nullable();
            $table->double('new_system_fund', 15, 2)->nullable();
            $table->string('step');
             // 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_deposit_activits');
    }
}
