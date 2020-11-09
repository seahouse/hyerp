<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceningattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('epcsecening_id')->unsigned();
            $table->string('type')->nullable();             // 图片: image, 文件: file，双方签字的安装队工作量表：bothsigned，华星东方下发的工作联系单：huaxingworksheet，安装队下发的工作联系单：installworksheet，增补之前图片：beforeimage，增补施工后图片：afterimage
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('epcsecening_id')->references('id')->on('epcsecenings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('epcseceningattachments');
    }
}
