<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prheads', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number')->unique();
            $table->integer('dayseq')->default(0);
            $table->integer('company_id')->unsigned();
            $table->integer('sohead_id')->unsigned();
            $table->integer('status')->default(1);              // 状态。1：初始值
            $table->string('remark')->nullable();
            $table->string('type')->nullable();
            $table->integer('applicant_id')->unsigned();
            $table->string('approval_type')->nullable();
            $table->string('process_instance_id')->nullable();

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
        Schema::drop('prheads');
    }
}
