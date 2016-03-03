<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soitems', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('sohead_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->date('scheddate')->nullable();
            $table->decimal('qty', 18, 6);
            $table->decimal('unitcost', 18, 6)->default(0.0);
            $table->decimal('price', 16, 6);
            $table->decimal('custprice', 16, 6)->default(0.0);
            $table->decimal('qtyshipped', 18, 6)->default(0.0);
            
            $table->timestamps();

            $table->foreign('sohead_id')->references('id')->on('soheads');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('soitems');
    }
}
