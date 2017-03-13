<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseroldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userolds', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->integer('user_hxold_id');

            $table->timestamps();

//            $table->unique('user_hxold_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('userolds');
    }
}
