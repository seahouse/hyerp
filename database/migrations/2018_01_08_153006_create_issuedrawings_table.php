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
            $table->integer('sohead_id');
            $table->string('overview')->default('');
            $table->decimal('tonnage', 18, 2)->default(0.0);
            $table->string('productioncompany');
            $table->string('materialsupplier');
            $table->integer('drawingchecker_id');
            $table->date('requestdeliverydate');
            $table->integer('drawingcount')->default(0);
            $table->string('remark')->default('');
            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1-初始，0-已付款
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
