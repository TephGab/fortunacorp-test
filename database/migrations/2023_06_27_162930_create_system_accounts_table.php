<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_accounts', function (Blueprint $table) {
            $table->id();
            $table->double('revenues', 15, 2)->default(0.0);
            $table->double('funds', 15, 2)->default(0.0);
            $table->double('claims', 15, 2)->default(0.0);
            $table->double('depts', 15, 2)->default(0.0);
            $table->double('user_payroll', 15, 2)->default(0.0);
            $table->double('employee_payroll', 15, 2)->default(0.0);
            $table->double('agent_investments', 15, 2)->default(0.0);
            $table->foreignId('user_id')->default(1)->constrained();
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
        Schema::dropIfExists('system_accounts');
    }
}
