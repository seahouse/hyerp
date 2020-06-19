<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionsalesordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionsalesorders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('sohead_id')->unsigned();
            $table->string('signcontract_condition');
            $table->string('reason')->nullable();
            $table->string('remark')->nullable();

            $table->integer('applicant_id');
            $table->integer('status')->default(1);              // 状态：1表示初始开始状态, 0表示已结束（同意）, -1表示结束（拒绝）,-2表示已撤销
            $table->string('process_instance_id')->default('');
            $table->string('business_id')->default('');

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
        Schema::drop('additionsalesorders');
    }
}
