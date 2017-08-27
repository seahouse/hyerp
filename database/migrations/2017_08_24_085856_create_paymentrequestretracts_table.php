<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentrequestretractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentrequestretracts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('paymentrequest_id');
            $table->string('retractreason')->default('');            // 撤回原因
            $table->integer('applicant_id');							// 申请人
            $table->integer('status')->default(1);						// 状态：1-初始，0-已通过
            $table->integer('approversetting_id');          			// 下一个审批流程id, 0表示已经走完流程, -1表示没有设置审批流，-2表示流程已走完，未通过

//            $table->integer('paymentrequest_id');
//            $table->integer('level');
//            $table->integer('approver_id');
//            $table->integer('status');
//            $table->string('description');

            $table->timestamps();

            $table->foreign('paymentrequest_id')->references('id')->on('paymentrequests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('paymentrequestretracts');
    }
}
