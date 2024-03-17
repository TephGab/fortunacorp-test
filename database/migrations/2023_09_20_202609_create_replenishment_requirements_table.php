<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplenishmentRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replenishment_requirements', function (Blueprint $table) {
            $table->id();
            $table->double('replenishment', 15, 2)->default(0.0);
            $table->double('dept', 15, 2)->default(0.0);
            $table->double('required_amount', 15, 2)->default(0.0);
            $table->string('status')->nullable()->default('pending');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('transaction_id')->constrained();
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
        Schema::dropIfExists('replenishment_requirements');
    }
}
