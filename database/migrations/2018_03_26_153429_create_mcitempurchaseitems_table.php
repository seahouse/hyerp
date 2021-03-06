<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcitempurchaseitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcitempurchaseitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('mcitempurchase_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->string('size')->default('');
            $table->string('material')->default('');
            $table->decimal('unitprice', 18, 2)->default(0.0);
            $table->decimal('quantity', 18, 2)->default(0.0);
            $table->integer('unit_id')->nullable();
            $table->decimal('weight', 18, 2)->default(0.0);
            $table->string('remark')->default('');
            $table->integer('seq')->default(0);

            $table->timestamps();

            $table->foreign('mcitempurchase_id')->references('id')->on('mcitempurchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mcitempurchaseitems');
    }
}
