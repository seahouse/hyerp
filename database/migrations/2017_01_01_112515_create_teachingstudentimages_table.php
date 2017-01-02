<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachingstudentimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachingstudentimages', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('image_id');
            $table->integer('teachingpoint_id');

            $table->timestamps();

            $table->foreign('teachingpoint_id')->references('id')->on('teachingpoints');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teachingstudentimages');
    }
}
