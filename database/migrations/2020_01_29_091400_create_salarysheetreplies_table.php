<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalarysheetrepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarysheetreplies', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('salarysheet_id')->unsigned();
            $table->integer('status');                           // 状态。0：确认无误；-1：存在异议
            $table->string('message')->nullable();

            $table->timestamps();

            $table->foreign('salarysheet_id')->references('id')->on('salarysheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salarysheetreplies');
    }
}
