<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedrawingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuedrawings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('designdepartment');
            $table->integer('company_id')->nullable();
            $table->integer('sohead_id');
            $table->string('overview')->default('');
            $table->string('cabinetname')->default('');             // 柜体名称
            $table->decimal('cabinetquantity', 18, 2)->default(0.0);             // 柜体数量
            $table->decimal('sheet_thickness')->default(0.0);               // 薄板厚度
            $table->decimal('steel_thickness')->default(0.0);               // 型钢厚度
            $table->decimal('tonnage', 18, 4)->default(0.0);
            $table->string('productioncompany');                            // 原字段
            $table->integer('productioncompany_id')->nullable();            // 制作公司id，新加字段，2021/1/30，统一公司
            $table->integer('outsourcingcompany_id')->nullable();
            $table->string('materialsupplier');                             // 原字段
            $table->integer('materialsupplier_id')->nullable();             // 材料供应方，新加字段，2021/1/30，统一公司
            $table->integer('drawingchecker_id');
            $table->date('requestdeliverydate');
            $table->smallInteger('bolt')->default(0);               // 是否栓接
            $table->integer('drawingcount')->default(0);
            $table->string('remark')->default('');
            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝），-2表示撤销
            $table->integer('approversetting_id');              // 下一个审批流程id, 0表示已经走完流程, -1表示没有流程可以走，-2表示流程已走完，未通过，-3表示撤回过程中，-4表示已撤回

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
        Schema::drop('issuedrawings');
    }
}
