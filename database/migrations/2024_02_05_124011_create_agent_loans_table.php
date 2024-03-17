<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_loans', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2);
            $table->string('status')->default('pending');
            $table->string('type')->default('start_loan')->nullable();
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');
            $table->foreignId('user_id')->constrained();
            $table->integer('receiver_id');
            $table->text('comment')->nullable();
            $table->boolean('confirmed_by_receiver')->default(0)->nullable();
            $table->timestamp('receiver_confirmation_date')->nullable();
            $table->integer('payment_progress')->default(0)->nullable();
            $table->string('payment_status')->default('unpaid')->nullable();
            $table->double('commission_payment', 15, 2)->default(0)->nullable();
            $table->double('deposit_payment', 15, 2)->default(0)->nullable();
            $table->integer('loan_percentage')->default(0)->nullable();
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
        Schema::dropIfExists('agent_loans');
    }
}