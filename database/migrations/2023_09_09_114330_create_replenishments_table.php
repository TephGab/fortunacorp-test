<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplenishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replenishments', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2)->nullable();
            $table->set('status', ['pending', 'envoy_confirmed', 'receiver_confirmed', 'partially_completed', 'confirmed', 'completed'])->default('pending');
            $table->set('type', ['user_account', 'user_bank_account'])->default('user_account');
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');
            $table->text('comment')->nullable();
            $table->integer('system_bank_account_id')->nullable();
            $table->integer('system_account_id')->nullable();
            $table->integer('user_bank_account_id')->nullable();
            $table->integer('user_account_id')->nullable();
            $table->integer('sender_id')->nullable();
            $table->integer('receiver_id')->nullable(); 
            $table->integer('envoy_id')->nullable();
            $table->boolean('confirmed_by_receiver')->default(0)->nullable();
            $table->boolean('confirmed_by_envoy')->default(0)->nullable(); 
            $table->timestamp('initialization_date')->nullable();
            $table->timestamp('receiver_confirmation_date')->nullable();
            $table->timestamp('envoy_confirmation_date')->nullable();
            $table->timestamps();
            $table->boolean('isAreqReplenish')->default(false);
            $table->integer('required_replenishment_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replenishments');
    }
}
