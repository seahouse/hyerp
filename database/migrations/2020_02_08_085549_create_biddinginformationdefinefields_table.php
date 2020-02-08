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
        Schema::drop('biddinginformationdefinefields');
    }
}
