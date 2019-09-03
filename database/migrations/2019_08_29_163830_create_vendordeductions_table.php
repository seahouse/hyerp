<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendordeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendordeductions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pohead_id')->unsigned();
            $table->integer('outsourcing_id')->unsigned()->nullable();
            $table->string('outsourcingtype');
            $table->string('techdepart');               // 技术部门
            $table->string('problemlocation');               // 扣款问题发生地
            $table->string('reason', 500)->default('');
            $table->string('remark', 500)->default('');

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
        Schema::drop('vendordeductions');
    }
}
