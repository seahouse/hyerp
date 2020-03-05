<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationdefinefieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformationdefinefields', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('sort')->default(1);
            $table->integer('type')->default(1);                // 1: 字符串
            $table->string('exceltype')->nullable();            // 汇总表, 项目明细
            $table->string('projecttype')->nullable();          // SDA, SCR, WET, SNCR, FAS（fly ash stable）, COM, CFB, BHF

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
        Schema::drop('biddinginformationdefinefields');
    }
}
