<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedrawingmodifyweightlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuedrawingmodifyweightlogs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('issuedrawing_id');
            $table->decimal('oldtonnage', 18, 4);
            $table->decimal('tonnage', 18, 4);
            $table->string('reason')->default('');
            $table->integer('operator_id');

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
        Schema::drop('issuedrawingmodifyweightlogs');
    }
}
