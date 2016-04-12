<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soheads', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('number')->unique();
            $table->string('descrip')->nullable();              // 工程名称
            $table->integer('custinfo_id')->nullable();
            $table->date('orderdate')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('shipto')->nullable();
            $table->integer('salesrep_id')->nullable();
            $table->integer('term_id')->nullable();
            $table->string('comments')->nullable();
            
            $table->timestamps();

            $table->foreign('custinfo_id')->references('id')->on('custinfos');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('salesrep_id')->references('id')->on('salesreps');
            $table->foreign('term_id')->references('id')->on('terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('soheads');
    }
}
