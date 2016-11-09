<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentrequestattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentrequestattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('paymentrequest_id');
            $table->string('type')->nullable();             // 付款节点审批单: paymentnode, 商务合同: businesscontract, 图片: image, 
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

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
        Schema::drop('paymentrequestattachments');
    }
}
