<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('name');
            $table->set('type', ['moncash', 'natcash', 'local']);
            $table->double('balance', 15, 2)->default(0.0);
            $table->set('category', ['normal', 'business'])->default('normal');
            $table->boolean('full_wallet')->default(true);
            $table->timestamps();
            $table->integer('total_monthly_transactions')->default(0);
            $table->foreignId('user_id')->constrained()->default(1);
            $table->integer('can_be_used_by')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
