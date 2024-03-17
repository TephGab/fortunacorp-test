<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashinActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashin_activits', function (Blueprint $table) {
            $table->id();
            $table->double('amount',30, 2);
            $table->double('rate', 8, 2)->nullable();
            $table->set('type', ['moncash', 'natcash', 'local']);
            $table->set('operation', ['reception', 'reception_si']);
            $table->set('status', ['pending', 'aproved', 'completed']);
            $table->foreignId('provider_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('cashin_id')->constrained();

            // 
            $table->double('current_admin_balance', 15, 2)->nullable();
            $table->double('new_admin_balance', 15, 2)->nullable();
            $table->double('current_system_debt', 15, 2)->nullable();
            $table->double('new_system_debt', 15, 2)->nullable();
            $table->double('current_provider_claim', 15, 2)->nullable();
            $table->double('new_provider_claim', 15, 2)->nullable();
            $table->double('current_account_balance', 15, 2)->nullable();
            $table->double('new_account_balance', 15, 2)->nullable();
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
        Schema::dropIfExists('cashin_activits');
    }
}
