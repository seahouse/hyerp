<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceninghumandaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceninghumandays', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('epcsecening_id')->unsigned();
            $table->string('humandays_type');
            $table->decimal('humandays')->default(0);
            $table->decimal('humandays_unitprice')->default(0.0);
//            $table->decimal('humandays_totalprice',8,2);
            $table->string('remark')->nullable();

            $table->timestamps();

            $table->foreign('epcsecening_id')->references('id')->on('epcsecenings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('epcseceninghumandays');
    }
}
