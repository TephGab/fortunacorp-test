<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');
            $table->text('comment')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->double('current_revenues', 15, 2)->nullable();
            $table->double('new_revenues', 15, 2)->nullable();
            $table->double('current_funds', 15, 2)->nullable();
            $table->double('new_funds', 15, 2)->nullable();
            $table->string('type')->nullable();
            $table->string('deduct_from')->nullable();
            $table->timestamp('expense_date')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
