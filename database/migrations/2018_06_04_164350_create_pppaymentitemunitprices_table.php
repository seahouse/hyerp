<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePppaymentitemunitpricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pppaymentitemunitprices', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pppaymentitem_id')->nullable();
            $table->string('name');
            $table->decimal('unitprice', 18, 2)->default(0.0);
            $table->decimal('tonnage', 18, 4)->default(0.0);

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
        Schema::drop('pppaymentitemunitprices');
    }
}
