<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporatepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corporatepayments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('position');
            $table->string('amounttype');
            $table->string('remark')->nullable();
            $table->decimal('amount')->default(0.0);
            $table->date('paydate');
            $table->string('paymentmethod');
            $table->integer('supplier_id');
            $table->integer('vendbank_id')->nullable();
            $table->string('associated_approval_projectpurchase')->nullable();

            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝）,-2表示已撤销
            $table->string('process_instance_id')->default('');
            $table->string('business_id')->default('');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('corporatepayments');
    }
}
