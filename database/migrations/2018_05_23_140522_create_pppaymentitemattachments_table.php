<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePppaymentitemattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pppaymentitemattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pppaymentitem_id');
            $table->string('type')->nullable();             // 付款节点审批单: drawing, 图片: image,
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('pppaymentitem_id')->references('id')->on('pppaymentitems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pppaymentitemattachments');
    }
}
