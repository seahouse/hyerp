<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReimbursementtravelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursementtravels', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('reimbursement_id')->nullable();
            $table->date('datego');
            $table->date('dateback');
            $table->string('descrip');

            $table->timestamps();

            $table->foreign('reimbursement_id')->references('id')->on('reimbursements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reimbursementtravels');
    }
}
