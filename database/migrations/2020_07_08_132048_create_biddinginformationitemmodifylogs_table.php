<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationitemmodifylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformationitemmodifylogs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('biddinginformationitem_id')->unsigned();
            $table->string('oldvalue')->nullable();
            $table->string('value')->nullable();
            $table->boolean('isclarify')->default(false);

            $table->timestamps();

            $table->foreign('biddinginformationitem_id')->references('id')->on('biddinginformationitems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('biddinginformationitemmodifylogs');
    }
}
