<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedrawingtonnagedetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuedrawingtonnagedetails', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('issuedrawing_id')->nullable();
            $table->string('name');
            $table->decimal('unitprice', 18, 2)->default(0.0);
            $table->decimal('tonnage', 18, 4)->default(0.0);

            $table->timestamps();

            $table->foreign('issuedrawing_id')->references('id')->on('issuedrawings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('issuedrawingtonnagedetails');
    }
}
