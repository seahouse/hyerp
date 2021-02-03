<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningcranesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceningcranes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('epcsecening_id')->unsigned();
            $table->string('crane_type');
            $table->decimal('number')->default(0);
            $table->decimal('unitprice')->default(0);
//            $table->decimal('totalprice',8,2);

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
        Schema::drop('epcseceningcranes');
    }
}
