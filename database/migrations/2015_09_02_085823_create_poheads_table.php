<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoheadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poheads', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('status')->default(0);
            $table->string('number');
            $table->string('descrip')->nullable();              // 工程名称
            $table->integer('vendinfo_id');
            $table->date('orderdate')->nullable();
            $table->string('fob')->nullable();      
            $table->string('shipvia')->nullable();     
            $table->string('comments')->nullable();
            $table->decimal('freight', 16, 2)->default(0.0);
            $table->integer('term_id')->nullable();
            $table->integer('vend_contact_id')->nullable();
            $table->string('vendaddress')->nullable();
            $table->integer('shipto_account_id')->nullable();
            $table->string('shiptoaddress')->nullable();
            $table->integer('sohead_id')->nullable();
            $table->date('releasedate')->nullable();
            
            $table->timestamps();

            $table->foreign('vendinfo_id')->references('id')->on('vendinfos');
            $table->foreign('term_id')->references('id')->on('terms');
            $table->foreign('vend_contact_id')->references('id')->on('contacts');
            $table->foreign('shipto_account_id')->references('id')->on('contacts');
            $table->foreign('sohead_id')->references('id')->on('soheads');
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
        Schema::drop('poheads');
    }
}
