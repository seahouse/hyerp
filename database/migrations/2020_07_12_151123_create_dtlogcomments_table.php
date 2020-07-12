<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtlogcommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtlogcomments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dtlog_id')->unsigned();
            $table->string('content');
            $table->dateTime('create_time');
            $table->string('userid');

            $table->timestamps();

            $table->foreign('dtlog_id')->references('id')->on('dtlogs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dtlogcomments');
    }
}
