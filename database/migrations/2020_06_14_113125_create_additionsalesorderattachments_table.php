<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionsalesorderattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionsalesorderattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('additionsalesorder_id')->unsigned();
            $table->string('type')->nullable();             // 图片: image, 文件: file
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('additionsalesorder_id')->references('id')->on('additionsalesorders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('additionsalesorderattachments');
    }
}
