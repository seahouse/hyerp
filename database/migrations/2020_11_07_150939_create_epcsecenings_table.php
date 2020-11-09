<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcsecenings', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->integer('sohead_id')->unsigned();
            $table->integer('supplier_id')->unsigned();
            $table->integer('pohead_id')->unsigned();
            $table->string('additional_design_department');
            $table->string('additional_source');
            $table->string('additional_source_department');
            $table->string('additional_reason');
            $table->string('need_issuedrawing');
            $table->string('design_change_sheet')->nullable();
            $table->string('short_additional_reason')->nullable();
            $table->string('drawing_additional_reason')->nullable();
            $table->string('extra_additional_reason')->nullable();
            $table->string('owner_additional_reason')->nullable();
            $table->string('owner_additional_reasonalreason')->nullable();
            $table->string('coordinate_additional_reason')->nullable();
            $table->string('additional_reason_detaildesc');
            $table->string('additional_content');
            $table->string('associated_approval_vendordeduction')->nullable();
            $table->string('associated_approval_designchangenotice')->nullable();

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
        Schema::drop('epcsecenings');
    }
}
