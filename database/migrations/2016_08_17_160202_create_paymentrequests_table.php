<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentrequests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('suppliertype')->default('');                // 供应商类型：安装公司、机务设备类、电气设备类、安装材料类、代理或服务类、厂部常用类、其他
            $table->string('paymenttype')->default('');                 // 付款类型：预付款、进度款、到货款、安装结束款、调试运行款、环保验收款、质保金
            $table->integer('supplier_id');         	// 供应商
            $table->integer('pohead_id');           	// 采购订单ID
            $table->string('equipmentname')->default('');               // 设备名称
            $table->string('descrip')->default('');                     // 付款说明
            $table->decimal('amount', 18, 2)->default(0.0);             // 付款金额
            $table->string('paymentmethod')->nullable();                // 付款方式：支票、贷记、电汇、汇票、现金、银行卡、其他
			$table->date('datepay')->nullable();                        // 支付日期
			$table->integer('vendbank_id')->nullable();            		// 银行账号
            $table->string('bank')->nullable();							// 开户行
            $table->string('bankaccountnumber')->nullable();            // 银行账号
			$table->integer('applicant_id');							// 申请人
			$table->integer('status')->default(1);						// 状态：1-初始，0-已付款
			$table->integer('approversetting_id');          			// 下一个审批流程id, 0表示已经走完流程, -1表示没有流程可以走，-2表示流程已走完，未通过

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
        Schema::drop('paymentrequests');
    }
}
