<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendordeductionitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendordeductionitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('vendordeduction_id')->unsigned();
            $table->string('itemname');
            $table->string('itemspec')->nullable();
            $table->string('itemunit')->nullable();
            $table->decimal('quantity')->default(0.0);
            $table->decimal('unitprice')->default(0.0);
            $table->integer('seq')->default(0);

            $table->timestamps();

            $table->foreign('vendordeduction_id')->references('id')->on('vendordeductions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendordeductionitems');
    }
}
