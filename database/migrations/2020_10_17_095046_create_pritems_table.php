<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePritemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pritems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('prhead_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->decimal('quantity')->default(0.0);
            $table->string('remark')->nullable();

            $table->timestamps();

            $table->foreign('prhead_id')->references('id')->on('prheads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pritems');
    }
}
