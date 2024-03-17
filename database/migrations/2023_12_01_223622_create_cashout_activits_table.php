<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashoutActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashout_activits', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->string('status')->default('pending');
            $table->set('type', ['user_account', 'user_bank_account'])->default('user_account');
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');
            $table->text('comment')->nullable();
            $table->integer('user_bank_account_id')->nullable();
            $table->integer('system_bank_account_id')->nullable();
            $table->integer('user_account_id')->nullable();
            $table->integer('admin_id')->nullable(); 
            $table->integer('envoy_id')->nullable(); 
            $table->integer('user_id')->constrained();
            $table->foreignId('cashout_id')->constrained();
            $table->boolean('user_in_person')->default(0)->nullable();
            $table->boolean('use_envoy_debt')->default(0)->nullable();
            $table->string('user_role')->nullable();
            $table->boolean('completed_by_admin')->default(0)->nullable();
            $table->boolean('confirmed_by_user')->default(0)->nullable();
            $table->boolean('confirmed_by_envoy')->default(0)->nullable(); 
            $table->timestamp('admin_completion_date')->nullable();
            $table->timestamp('receiver_confirmation_date')->nullable();
            $table->timestamp('envoy_confirmation_date')->nullable();
            $table->timestamps();
            // 
            $table->double('current_user_balance', 15, 2)->nullable();
            $table->double('new_user_balance', 15, 2)->nullable();
            $table->double('current_envoy_debt', 15, 2)->nullable();
            $table->double('new_envoy_debt', 15, 2)->nullable();
            $table->double('current_system_fund', 15, 2)->nullable();
            $table->double('new_system_fund', 15, 2)->nullable();
            $table->string('step');
            $table->string('commission_category')->default('commission');
            // 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashout_activits');
    }
}
