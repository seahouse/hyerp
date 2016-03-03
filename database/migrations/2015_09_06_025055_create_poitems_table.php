<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poitems', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('status')->default(0);
            $table->integer('pohead_id')->nullable();
            $table->integer('itemsite_id')->nullable();
            $table->date('duedate')->nullable();
            $table->decimal('qty_ordered', 18, 6)->default(0.0);
            $table->decimal('qty_received', 18, 6)->default(0.0);
            $table->decimal('unitprice', 16, 6)->nullable();
            $table->string('comments')->nullable();
            $table->decimal('freight', 16, 4)->default(0.0);
            
            $table->timestamps();

            $table->foreign('pohead_id')->references('id')->on('poheads');
            $table->foreign('itemsite_id')->references('id')->on('itemsites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('poitems');
    }
}
