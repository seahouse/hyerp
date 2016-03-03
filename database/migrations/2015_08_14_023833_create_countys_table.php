<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countys', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->integer('city_id')->nullable();
            
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('citys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countys');
    }
}
