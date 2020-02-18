<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsitepurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectsitepurchases', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('purchasecompany_id')->nullable();
            $table->integer('sohead_id');
            $table->string('projecttype');
            $table->string('vendordeduction_descrip');
            $table->string('designdept');
            $table->string('productiondept');
            $table->string('purchasetype');
            $table->string('purchasereason');
            $table->string('remark', 500)->default('');
            $table->decimal('freight', 18, 2)->default(0.0);
            $table->decimal('totalprice', 18, 2)->default(0.0);
            $table->string('paymentmethod');
            $table->string('invoicesituation');
            $table->string('companyname')->nullable();
            $table->string('contact')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('otherremark')->default('');

            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝）,-2表示已撤销
//            $table->integer('approversetting_id');              // 下一个审批流程id, 0表示已经走完流程, -1表示没有流程可以走，-2表示流程已走完，未通过，-3表示撤回过程中，-4表示已撤回
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
        Schema::drop('projectsitepurchases');
    }
}
