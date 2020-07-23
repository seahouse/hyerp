<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBiddinginformationeditemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biddinginformationeditems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('biddinginformation_id')->unsigned();
            $table->string('key');
            $table->string('value');
            $table->string('remark', 4000)->nullable();
            $table->integer('sort');
            $table->integer('type');

            $table->timestamps();

            $table->foreign('biddinginformation_id')->references('id')->on('biddinginformations')->onDelete('cascade');

            $table->index('biddinginformation_id', 'key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('biddinginformationeditems');
    }
}
