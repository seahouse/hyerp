<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformationitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('biddinginformation_id')->unsigned();
            $table->string('key');
            $table->string('value');
            $table->string('remark', 3000)->nullable();
            $table->integer('sort');
            $table->integer('type');

            $table->timestamps();

            $table->foreign('biddinginformation_id')->references('id')->on('biddinginformations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('biddinginformationitems');
    }
}
