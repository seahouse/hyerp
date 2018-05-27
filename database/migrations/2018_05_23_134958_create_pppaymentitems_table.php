<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePppaymentitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pppaymentitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pppayment_id')->nullable();
            $table->integer('sohead_id')->nullable();
            $table->string('productionoverview')->default('');
            $table->decimal('tonnage', 18, 2)->default(0.0);
//            $table->decimal('quantity', 18, 2)->default(0.0);
//            $table->integer('unit_id')->nullable();
//            $table->decimal('weight', 18, 2)->default(0.0);
//            $table->string('remark')->default('');
            $table->integer('seq')->default(0);

            $table->timestamps();

            $table->foreign('pppayment_id')->references('id')->on('pppayments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pppaymentitems');
    }
}
