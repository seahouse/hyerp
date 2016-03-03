<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('number')->unique();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->integer('contact_id')->nullable();
            $table->integer('dept_id')->nullable();
            $table->string('notes')->nullable();
            $table->integer('image_id')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            
            $table->timestamps();
            
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('dept_id')->references('id')->on('depts');
            $table->foreign('image_id')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
