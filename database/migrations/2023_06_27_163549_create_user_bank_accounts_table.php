<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->double('balance', 15, 2)->default(0.0);
            $table->set('currency', ['us', 'htg', 'pesos'])->default('us');
            $table->string('bank_name')->nullable();
            $table->string('category')->nullable();
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
        Schema::dropIfExists('user_bank_accounts');
    }
}
