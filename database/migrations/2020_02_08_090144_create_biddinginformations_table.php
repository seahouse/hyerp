<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number');
            $table->integer('year');
            $table->integer('digital_number');
            $table->smallInteger('closed')->default(0);
            $table->string('remark', 1000)->nullable();

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
        Schema::drop('biddinginformations');
    }
}
