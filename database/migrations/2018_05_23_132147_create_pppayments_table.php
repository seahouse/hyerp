<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePppaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Production processing bill payment
        Schema::create('pppayments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('productioncompany');
            $table->string('designdepartment');
            $table->string('paymentreason')->default('');
//            $table->date('expirationdate');
//            $table->integer('sohead_id');
//            $table->decimal('totalprice', 18, 2)->default(0.0);
            $table->string('invoicingsituation')->default('');
            $table->decimal('totalpaid', 18, 2)->default(0.0);
            $table->decimal('amount', 18, 2)->default(0.0);
            $table->date('paymentdate');
            $table->integer('supplier_id');
            $table->integer('vendbank_id')->nullable();
            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝）,-2表示已撤销
            $table->integer('approversetting_id');              // 下一个审批流程id, 0表示已经走完流程, -1表示没有流程可以走，-2表示流程已走完，未通过，-3表示撤回过程中，-4表示已撤回

            $table->string('process_instance_id')->default('');
            $table->string('business_id')->default('');
            $table->string('syncdtdesc')->nullable();           // 同步到钉钉组织

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
        Schema::drop('pppayments');
    }
}
