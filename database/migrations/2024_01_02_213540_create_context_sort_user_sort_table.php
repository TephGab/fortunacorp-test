<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContextSortUserSortTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('context_sort_user_sort', function (Blueprint $table) {
            $table->unsignedBigInteger('context_sort_id');
            $table->unsignedBigInteger('user_sort_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('context_sort_id')->references('id')->on('context_sorts')->onDelete('cascade');
            $table->foreign('user_sort_id')->references('id')->on('user_sorts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Define a shorter name for the primary key constraint
            $table->primary(['context_sort_id', 'user_sort_id', 'user_id'], 'pk_context_user');

            // You may add additional columns as needed
            // $table->string('additional_column');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('context_sort_user_sort');
    }
}
