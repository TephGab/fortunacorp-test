<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransfertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transferts', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2);
            $table->foreignId('user_id')->constrained();
            $table->integer('account_sender_id');
            $table->string('account_sender_name'); 
            $table->string('account_sender_phone'); 
            $table->integer('account_receiver_id');
            $table->string('account_receiver_name'); 
            $table->string('account_receiver_phone');
            $table->timestamps();
            $table->boolean('countable')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_transferts');
    }
}
