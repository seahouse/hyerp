<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcitempurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Manufacturing Center Item Purchase
        Schema::create('mcitempurchases', function (Blueprint $table) {
            $table->increments('id');

            $table->string('manufacturingcenter');
            $table->integer('manufacturingcenter_id')->nullable();          // 新增字段，2020/1/30
            $table->string('itemtype');
            $table->date('expirationdate');
            $table->integer('sohead_id');
//            $table->integer('issuedrawing_id')->nullable();
            $table->decimal('totalprice', 18, 2)->default(0.0);
            $table->string('detailuse')->default('');
            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝）,-2表示已撤销
            $table->integer('approversetting_id');              // 下一个审批流程id, 0表示已经走完流程, -1表示没有流程可以走，-2表示流程已走完，未通过，-3表示撤回过程中，-4表示已撤回

            $table->string('process_instance_id')->default('');
            $table->string('business_id')->default('');

            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('issuedrawing_id')->references('id')->on('issuedrawings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mcitempurchases');
    }
}
