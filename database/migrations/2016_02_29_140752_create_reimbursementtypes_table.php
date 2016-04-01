<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// 报销类别
class CreateReimbursementtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursementtypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');             	// 名称
            $table->string('descrip')->nullable();	// 说明
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
        Schema::drop('reimbursementtypes');
    }
}
