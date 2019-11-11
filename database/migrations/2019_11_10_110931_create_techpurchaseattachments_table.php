<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechpurchaseattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techpurchaseattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('techpurchase_id');
            $table->string('type')->nullable();
            $table->string('filename')->nullable();
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('techpurchase_id')->references('id')->on('techpurchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('techpurchaseattachments');
    }
}
