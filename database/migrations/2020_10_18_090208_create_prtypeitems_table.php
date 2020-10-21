<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrtypeitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prtypeitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('prtype_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('quantity')->default(0.0);

            $table->timestamps();

            $table->foreign('prtype_id')->references('id')->on('prtypes')->onDelete('cascade');
            $table->unique(['prtype_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prtypeitems');
    }
}
