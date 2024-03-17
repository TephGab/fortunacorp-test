<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('id_number')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_picture')->nullable()->default('default-user.png');
            $table->string('avatar')->default('default-user.png');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('address');
            $table->string('location');
            $table->double('limit_min', 15, 2)->nullable();
            $table->double('limit_max', 15, 2)->nullable();
            $table->integer('percentage')->default(25);
            $table->integer('reference')->nullable();
            $table->json('godson')->nullable();
            $table->integer('registered_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('online_status')->default('offline');
            $table->foreignId('department_id')->constrained()->default(1);
            $table->boolean('dept_allowed')->default(false);
            $table->integer('loan_percentage')->default(0);
            $table->integer('default_percentage')->default(25);
            $table->integer('envoy_id')->default(1);
            $table->string('sex')->default('m');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
