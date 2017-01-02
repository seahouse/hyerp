<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachingadministratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachingadministrators', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->string('number');
            $table->integer('teachingpoint_id');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teachingpoint_id')->references('id')->on('teachingpoints');
			
			$table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teachingadministrators');
    }
}
