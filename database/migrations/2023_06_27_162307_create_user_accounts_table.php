<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->double('balance', 15, 2)->default(0.0);
            $table->double('depts', 15, 2)->default(0.0);
            $table->double('withdrawal', 15, 2)->default(0.0);
            $table->string('category')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->double('replenishments', 15, 2)->default(0.0);
            $table->double('investments', 15, 2)->default(0.0);
            $table->double('profits', 15, 2)->default(0.0);
            $table->double('cash_in_hand', 15, 2)->default(0.0);
            $table->double('referral_commissions', 15, 2)->default(0.0)->nullable();
            $table->double('capital', 15, 2)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_accounts');
    }
}
