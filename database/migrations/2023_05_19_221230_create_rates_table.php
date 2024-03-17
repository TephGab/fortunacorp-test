<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->decimal('daily_rate_sale', 8, 2)->default(2.50);
            $table->decimal('daily_rate_purchase', 8, 2)->default(0.38);
            // $table->double('daily_rate_sale', 8, 2)->default(2.50);
            // $table->double('daily_rate_purchase', 8, 2)->default(2.50);
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
        Schema::dropIfExists('rates');
    }
}
