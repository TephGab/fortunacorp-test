<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentLoanTransactionRepaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_loan_transaction_repays', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2);
            $table->double('transaction_commission', 15, 2);
            $table->integer('loan_percentage')->default(0)->nullable();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('transaction_id')->constrained();

            $table->double('current_agent_debt', 15, 2)->nullable();
            $table->double('new_agent_debt', 15, 2)->nullable();
            $table->double('current_agent_commission', 15, 2)->nullable();
            $table->double('new_agent_commission', 15, 2)->nullable();
            $table->double('current_agent_capital', 15, 2)->nullable();
            $table->double('new_agent_capital', 15, 2)->nullable();


            $table->double('current_system_claim', 15, 2)->nullable();
            $table->double('new_system_claim', 15, 2)->nullable();

            $table->double('current_system_fund', 15, 2)->nullable();
            $table->double('new_system_fund', 15, 2)->nullable();

            $table->timestamp('completed_date')->nullable();

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
        Schema::dropIfExists('agent_loan_transaction_repays');
    }
}
