<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsitepurchaseitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectsitepurchaseitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('projectsitepurchase_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('brand')->default('');
            $table->integer('unit_id')->nullable();
            $table->decimal('quantity', 18, 2)->default(0.0);
            $table->decimal('unitprice', 18, 2)->default(0.0);
            $table->decimal('price', 18, 4)->default(0.0);
            $table->integer('seq')->default(0);

            $table->timestamps();

            $table->foreign('projectsitepurchase_id')->references('id')->on('projectsitepurchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('projectsitepurchaseitems');
    }
}
