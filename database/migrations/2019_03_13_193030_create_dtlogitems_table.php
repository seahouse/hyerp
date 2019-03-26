<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtlogitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtlogitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dtlog_id');
            $table->string('key');
            $table->string('value', 1000);
            $table->integer('sort');
            $table->integer('type');

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
        Schema::drop('dtlogitems');
    }
}
