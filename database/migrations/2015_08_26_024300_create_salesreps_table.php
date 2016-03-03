<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesrepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesreps', function (Blueprint $table) {
            $table->increments('id');
            
            $table->boolean('active')->default(true);
            $table->string('number')->unique();
            $table->string('name');
            $table->decimal('commission', 8, 4)->nullable();
            $table->integer('employee_id')->nullable();
            
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salesreps');
    }
}
