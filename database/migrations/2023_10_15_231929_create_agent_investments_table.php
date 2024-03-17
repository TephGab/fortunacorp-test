<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_investments', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->string('status')->default('confirmed');
            $table->string('type')->default('user_account');
            $table->string('currency')->default('pesos');
            $table->text('comment')->nullable();
            $table->double('current_balance', 15, 2)->nullable();
            $table->double('new_balance', 15, 2)->nullable();
            $table->double('current_depts', 15, 2)->nullable();
            $table->double('new_depts', 15, 2)->nullable();
            $table->double('current_investment', 15, 2)->nullable();
            $table->double('new_investment', 15, 2)->nullable();
            $table->foreignId('agent_deposit_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('agent_investments');
    }
}
