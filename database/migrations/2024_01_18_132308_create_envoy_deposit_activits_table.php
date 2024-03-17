<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvoyDepositActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envoy_deposit_activits', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->string('status')->default('pending');
            $table->set('type', ['user_account', 'user_bank_account'])->default('user_account');
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');;
            $table->text('comment')->nullable();
            $table->integer('user_account_id')->nullable();
            $table->integer('user_bank_account_id')->nullable();
            $table->integer('system_account_id')->nullable();
            $table->integer('system_bank_account_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->integer('receiver_id')->nullable();
            $table->boolean('confirmed_by_receiver')->default(0)->nullable();
            $table->timestamp('receiver_confirmation_date')->nullable();
            $table->double('current_balance', 15, 2)->nullable();
            $table->double('new_balance', 15, 2)->nullable();
            $table->double('current_depts', 15, 2)->nullable();
            $table->double('new_depts', 15, 2)->nullable();
            $table->foreignId('envoy_deposit_id')->constrained();
            // 
            $table->double('current_agent_balance', 15, 2)->nullable();
            $table->double('new_agent_balance', 15, 2)->nullable();
            $table->double('current_agent_investment', 15, 2)->nullable();
            $table->double('new_agent_investment', 15, 2)->nullable();
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
        Schema::dropIfExists('envoy_deposit_activits');
    }
}
