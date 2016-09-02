<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApproversettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approversettings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('approvaltype_id');
            $table->integer('approver_id')->default(0);
            $table->integer('dept_id')->nullable();
            $table->string('position')->nullable();
            $table->integer('level');                   // 审批层级，从1开始
            $table->string('descrip')->nullable();      // 说明

            $table->timestamps();

            $table->unique(['approvaltype_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('approversettings');
    }
}
