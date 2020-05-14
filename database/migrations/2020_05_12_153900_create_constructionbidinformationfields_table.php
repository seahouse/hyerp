<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstructionbidinformationfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constructionbidinformationfields', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('sort')->default(1);
            $table->integer('type')->default(1);                // 1: 字符串, 2: 单选
//            $table->string('exceltype')->nullable();            // 汇总表, 项目明细
            $table->string('projecttype')->nullable();          //
//            $table->string('select_strings')->nullable();       // 选择字段，用逗号分隔

            $table->timestamps();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('constructionbidinformationfields');
    }
}
