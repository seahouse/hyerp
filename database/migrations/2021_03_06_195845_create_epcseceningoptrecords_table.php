<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningoptrecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceningoptrecords', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('epcsecening_id')->unsigned();
            $table->string('userid');               // 操作人userid
            $table->dateTime('date');               // 操作时间
            $table->string('operation_type');       // 操作类型
            $table->string('operation_result');     // 操作结果
            $table->string('remark')->nullable();                // 评论内容

            $table->timestamps();

            $table->foreign('epcsecening_id')->references('id')->on('epcsecenings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('epcseceningoptrecords');
    }
}
