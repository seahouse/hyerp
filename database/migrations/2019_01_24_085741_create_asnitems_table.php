<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsnitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asnitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('asn_id')->nullable();
            $table->integer('poitemc_id')->nullable();
            $table->string('roll_no');
            $table->decimal('quantity');

            $table->timestamps();

            $table->foreign('asn_id')->references('id')->on('asns')->onDelete('cascade');
            $table->foreign('poitemc_id')->references('id')->on('poitemcs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('asnitems');
    }
}
