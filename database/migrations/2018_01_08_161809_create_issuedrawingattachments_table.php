<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedrawingattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuedrawingattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('issuedrawing_id');
            $table->string('type')->nullable();             // 付款节点审批单: drawing, 图片: image,
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('issuedrawing_id')->references('id')->on('issuedrawings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('issuedrawingattachments');
    }
}
