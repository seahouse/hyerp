<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationfieldtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformationfieldtypes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('biddinginformation_id')->unsigned();
            $table->string('biddinginformation_fieldtype');

            $table->timestamps();

            $table->foreign('biddinginformation_id')->references('id')->on('biddinginformations')->onDelete('cascade');
            $table->unique(['biddinginformation_id', 'biddinginformation_fieldtype']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('biddinginformationfieldtypes');
    }
}
