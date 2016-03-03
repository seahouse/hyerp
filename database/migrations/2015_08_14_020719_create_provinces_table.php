<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->integer('country_id')->nullable();
            
            $table->timestamps();
            
            $table->foreign('country_id')->references('id')->on('countrys')->onDelete('cascade');
            
//             $table->unique('name');      // not unique because maybe add country in the future.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('provinces');
    }
}
