<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningmaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceningmaterials', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('epcsecening_id')->unsigned();
            $table->string('material_type');
            $table->integer('item_id')->unsigned();
//            $table->string('price_unit')->nullable();
            $table->decimal('quantity')->default(0.0);
            $table->decimal('unitprice')->default(0.0);
            $table->string('remark')->nullable();

            $table->timestamps();

            $table->foreign('epcsecening_id')->references('id')->on('epcsecenings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('epcseceningmaterials');
    }
}
