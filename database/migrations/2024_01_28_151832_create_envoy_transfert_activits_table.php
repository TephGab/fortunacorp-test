<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvoyTransfertActivitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('envoy_transfert_activits', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2);
            $table->string('status')->default('pending');
            $table->set('type', ['user_account', 'user_bank_account'])->default('user_account');
            $table->set('currency', ['us', 'htg', 'pesos'])->default('pesos');
            $table->foreignId('user_id')->constrained();
            $table->integer('receiver_id');
            $table->text('comment')->nullable();
            $table->boolean('confirmed_by_receiver')->default(0)->nullable();
            $table->timestamp('receiver_confirmation_date')->nullable();
            $table->double('sender_current_depts', 15, 2)->nullable();
            $table->double('sender_new_depts', 15, 2)->nullable();
            $table->double('receiver_current_depts', 15, 2)->nullable();
            $table->double('receiver_new_depts', 15, 2)->nullable();
            $table->foreignId('envoy_transfert_id')->constrained();

             // 
             $table->double('current_system_claim', 15, 2)->nullable();
             $table->double('new_system_claim', 15, 2)->nullable();
             $table->double('current_sender_debt', 15, 2)->nullable();
             $table->double('new_sender_debt', 15, 2)->nullable();
             $table->double('current_sender_referral_commission', 15, 2)->nullable();
             $table->double('new_sender_referral_commission', 15, 2)->nullable();

             $table->double('current_receiver_debt', 15, 2)->nullable();
             $table->double('new_receiver_debt', 15, 2)->nullable();
             $table->double('current_receiver_referral_commission', 15, 2)->nullable();
             $table->double('new_receiver_referral_commission', 15, 2)->nullable();

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
        Schema::dropIfExists('envoy_transfert_activits');
    }
}
