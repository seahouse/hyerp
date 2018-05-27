<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePppaymentitemissuedrawingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pppaymentitemissuedrawings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pppaymentitem_id')->nullable();
            $table->integer('issuedrawing_id')->nullable();

            $table->timestamps();

            $table->foreign('pppaymentitem_id')->references('id')->on('pppaymentitems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pppaymentitemissuedrawings');
    }
}
