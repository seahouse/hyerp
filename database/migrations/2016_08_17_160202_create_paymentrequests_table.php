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

            $table->string('descrip');              // 付款事由
            $table->integer('supplier_id');         // 供应商
            $table->integer('pohead_id');           // 采购订单ID
            $table->decimal('amount', 18, 2)->default(0.0);             // 付款金额
            $table->string('paymentmethod');                            // 付款方式：现金、支票、转账、汇票
			$table->date('datepay')->nullable();                        // 支付日期
            $table->string('bank');                                     // 开户行
            $table->string('bankaccountnumber');                        // 银行账号

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
