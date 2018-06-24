<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operationlogs', function (Blueprint $table) {
            //
            $table->increments('id');

            $table->string('table_name');
            $table->string('table_id');
            $table->string('operation')->default('');
            $table->integer('operator_id');

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
        Schema::drop('operationlogs');
    }
}
