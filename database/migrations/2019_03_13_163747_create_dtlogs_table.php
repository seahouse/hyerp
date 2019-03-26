<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtlogs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('report_id');
            $table->dateTime('create_time');
            $table->string('creator_id');
            $table->string('creator_name');
            $table->string('dept_name')->nullable();
            $table->string('remark', 1000);
            $table->string('template_name');

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
        Schema::drop('dtlogs');
    }
}
