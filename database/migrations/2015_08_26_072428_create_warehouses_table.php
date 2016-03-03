<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('code')->unique();
            $table->string('descrip');
            $table->boolean('active')->default(true);
            $table->decimal('shipping_commission', 8, 4)->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('addr_id')->nullable();
            
            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('addr_id')->references('id')->on('addrs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('warehouses');
    }
}
