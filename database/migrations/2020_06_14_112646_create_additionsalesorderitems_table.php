<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionsalesorderitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additionsalesorderitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('additionsalesorder_id')->unsigned();
            $table->string('type');
            $table->string('otherremark');
            $table->string('unit');
            $table->decimal('quantity');
            $table->decimal('amount');

            $table->timestamps();

            $table->foreign('additionsalesorder_id')->references('id')->on('additionsalesorders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('additionsalesorderitems');
    }
}
