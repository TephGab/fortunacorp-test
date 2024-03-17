<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_activits', function (Blueprint $table) {
            $table->id();
            $table->double('sender_amount', 15, 2)->nullable();
            $table->double('receiver_amount', 15, 2)->nullable();
            $table->string('qr_code')->default(false)->nullable();
            $table->double('rate', 15, 2)->nullable();
            $table->double('fortuna_fee', 15, 2)->nullable();
            $table->double('p_to_p_fee', 15, 2)->nullable();
            $table->string('status');
            $table->set('type', ['moncash', 'natcash', 'local']);
            $table->set('operation', ['transfert', 'reception', 'transfert_si', 'reception_si']);
            $table->integer('sender')->nullable();
            $table->integer('receiver')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('completed_by')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('user_id');
            $table->integer('client_id');
            $table->foreignId('transaction_id');
            $table->text('comment')->nullable();
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
            $table->integer('operator_id')->nullable();
            $table->double('current_agent_balance', 15, 2)->nullable();
            $table->double('new_agent_balance', 15, 2)->nullable();
            $table->double('current_agent_investment', 15, 2)->nullable();
            $table->double('new_agent_investment', 15, 2)->nullable();
            $table->double('current_operator_balance', 15, 2)->nullable();
            $table->double('new_operator_balance', 15, 2)->nullable();
            $table->double('current_reference_balance', 15, 2)->nullable();
            $table->double('new_reference_balance', 15, 2)->nullable();
            $table->double('current_account_balance', 15, 2)->nullable();
            $table->double('new_account_balance', 15, 2)->nullable();
            $table->double('current_system_fund', 15, 2)->nullable();
            $table->double('new_system_fund', 15, 2)->nullable();
            $table->string('step');
            $table->timestamp('initialized_date')->nullable();
            $table->timestamp('edited_date')->nullable();
            $table->timestamp('resubmitted_date')->nullable();
            $table->timestamp('canceled_date')->nullable();
            $table->timestamp('disapproved_date')->nullable();
            $table->double('current_agent_cash_in_hand', 15, 2)->nullable();
            $table->double('new_agent_cash_in_hand', 15, 2)->nullable();
            $table->double('current_agent_capital', 15, 2)->nullable();
            $table->double('new_agent_capital', 15, 2)->nullable();
            $table->double('current_agent_debt', 15, 2)->nullable();
            $table->double('new_agent_debt', 15, 2)->nullable();
            $table->double('current_agent_referral_commission', 15, 2)->nullable();
            $table->double('new_agent_referral_commission', 15, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_activits');
    }
}
