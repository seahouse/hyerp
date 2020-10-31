<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prtypes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('prhead_id')->unsigned();               // 采购申请单ID
            $table->integer('supplier_id');

            $table->timestamps();

            $table->foreign('prhead_id')->references('id')->on('prheads');
            $table->unique(['prhead_id', 'supplier_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prtypes');
    }
}
