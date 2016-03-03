<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipitems', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('orderitem_id');
            $table->integer('shiphead_id')->nullable();     // for future use. forign key shipheads
            $table->decimal('quantity', 18, 6);
            $table->timestampTz('shipdate')->nullable();
            
            $table->timestamps();
            
            $table->index('orderitem_id');
            $table->index('shiphead_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shipitems');
    }
}
