<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentrequestretractapprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentrequestretractapprovals', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('paymentrequestretract_id');
            $table->integer('level');
            $table->integer('approver_id');
            $table->integer('status');
            $table->string('description');

            $table->timestamps();

            $table->foreign('paymentrequestretract_id')->references('id')->on('paymentrequestretracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('paymentrequestretractapprovals');
    }
}
