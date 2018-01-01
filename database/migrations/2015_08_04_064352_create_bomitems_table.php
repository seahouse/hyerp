<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBomitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bomitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('bomhead_id')->unsigned();
            $table->integer('seqnumber')->nullable()->default(0);
            $table->integer('item_id')->unsigned();
            $table->decimal('qtyper', 8, 6)->nullable()->default(0.0);
            $table->date('effective')->nullable();
            $table->date('expires')->nullable();
            
            $table->timestamps();

            $table->foreign('bomhead_id')->references('id')->on('bomheads')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->index(['bomhead_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bomitems');
    }
}
