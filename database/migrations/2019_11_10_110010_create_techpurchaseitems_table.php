<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechpurchaseitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techpurchaseitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('techpurchase_id')->unsigned();
            $table->integer('item_id');
            $table->decimal('quantity')->default(0.0);
            $table->string('descrip', 1000);

            $table->timestamps();

            $table->foreign('techpurchase_id')->references('id')->on('techpurchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('techpurchaseitems');
    }
}
