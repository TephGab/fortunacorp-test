<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfertProfitToRechargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfert_profit_to_recharges', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->double('current_balance', 15, 2)->nullable();
            $table->double('new_balance', 15, 2)->nullable();
            $table->double('current_depts', 15, 2)->nullable();
            $table->double('new_depts', 15, 2)->nullable();
            $table->double('current_investment', 15, 2)->nullable();
            $table->double('new_investment', 15, 2)->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->timestamp('completed_date')->nullable();
            $table->string('commission_category')->default('commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfert_profit_to_recharges');
    }
}
