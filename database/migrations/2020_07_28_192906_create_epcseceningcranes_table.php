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
            $table->integer('epcsecening_id');
            $table->string('crane_type');
            $table->integer('daysnumber');
            $table->decimal('price',8,2);
            $table->decimal('totalprice',8,2);
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
        Schema::drop('epcseceningcranes');
    }
}
