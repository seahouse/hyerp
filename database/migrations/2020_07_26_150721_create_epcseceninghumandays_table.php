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
            $table->integer('epcsecening_id');
            $table->string('humdays_type');
            $table->integer('humdays');
            $table->decimal('humdays_price',8,2);
            $table->decimal('humdays_totalprice',8,2);
            $table->string('remark');
            $table->timestamps();
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
