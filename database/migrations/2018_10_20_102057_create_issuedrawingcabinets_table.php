<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedrawingcabinetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issuedrawingcabinets', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('issuedrawing_id');
            $table->string('name')->default('');                            // 柜体名称
            $table->decimal('quantity', 18, 2)->default(0.0);               // 柜体数量

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
        Schema::drop('issuedrawingcabinets');
    }
}
