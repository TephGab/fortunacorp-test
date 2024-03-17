<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_payments', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 15, 2);
            // $table->double('rate', 8, 2)->nullable();
            $table->double('provider_current_claims', 15, 2)->nullable();
            $table->double('provider_new_claims', 15, 2)->nullable();
            $table->double('system_current_depts', 15, 2)->nullable();
            $table->double('system_new_depts', 15, 2)->nullable();
            $table->set('status', ['pending', 'aproved', 'completed']);
            $table->text('comment')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('provider_id');
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
        Schema::dropIfExists('provider_payments');
    }
}
