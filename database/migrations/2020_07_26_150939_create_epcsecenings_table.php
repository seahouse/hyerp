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
            $table->string('projectname')->nullable();
            $table->string('projectno')->nullable();
            $table->string('installingcompanyname')->nullable();
            $table->string('erpcontractno')->nullable();
            $table->string('erpcontractno')->nullable();
            $table->string('projectsalename')->nullable();
            $table->string('additional_design_department')->nullable();
            $table->string('additional_source')->nullable();
            $table->string('additional_source_department')->nullable();
            $table->string('additional_reason')->nullable();
            $table->string('additional_source')->nullable();
            $table->string('need_issuedrawing')->nullable();
            $table->string('design_change_sheetno')->nullable();
            $table->string('short_additional_reason')->nullable();
            $table->string('drawing_additional_reason')->nullable();
            $table->string('extra_additional_reason')->nullable();
            $table->string('owner_additional_reason')->nullable();
            $table->string('owner_additional_reasonalreason')->nullable();
            $table->string('coordinate_additional_reason')->nullable();
            $table->string('additional_reason_detaildesc')->nullable();
            $table->string('additional_detail')->nullable();
            $table->string('approvalno')->nullable();
            $table->string('design_approvalno')->nullable();
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝）,-2表示已撤销
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
        Schema::drop('epcsecenings');
    }
}
