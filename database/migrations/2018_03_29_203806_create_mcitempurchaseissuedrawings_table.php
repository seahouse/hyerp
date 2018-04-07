<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcitempurchaseissuedrawingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcitempurchaseissuedrawings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('mcitempurchase_id')->nullable();
            $table->integer('issuedrawing_id')->nullable();

            $table->timestamps();

            $table->foreign('mcitempurchase_id')->references('id')->on('mcitempurchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mcitempurchaseissuedrawings');
    }
}
