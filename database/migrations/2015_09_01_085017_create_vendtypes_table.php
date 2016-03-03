<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendtypes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('code');
            $table->string('descrip')->nullable();
            
            $table->timestamps();
            
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendtypes');
    }
}
