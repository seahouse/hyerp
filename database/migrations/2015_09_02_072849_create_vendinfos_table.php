<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendinfos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('po')->default(false);
            $table->string('comments')->nullable();
            $table->string('number');
            $table->integer('term_id')->nullable();
            $table->string('shipvia')->nullable();
            $table->integer('vendtype_id')->nullable();
            $table->integer('contact1_id')->nullable();
            $table->integer('contact2_id')->nullable();
            $table->integer('addr_id')->nullable();
            
            $table->timestamps();
            
            $table->foreign('term_id')->references('id')->on('terms');
            $table->foreign('vendtype_id')->references('id')->on('vendtypes');
            $table->foreign('contact1_id')->references('id')->on('contacts');
            $table->foreign('contact2_id')->references('id')->on('contacts');
            $table->foreign('addr_id')->references('id')->on('addrs');
            $table->unique('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendinfos');
    }
}
