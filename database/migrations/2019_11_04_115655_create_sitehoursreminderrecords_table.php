<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitehoursreminderrecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sitehoursreminderrecords', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('sohead_id');
            $table->integer('humandays');
            $table->dateTime('senddate');
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
        Schema::drop('sitehoursreminderrecords');
    }
}
