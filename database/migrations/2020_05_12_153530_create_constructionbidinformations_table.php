<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstructionbidinformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constructionbidinformations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number');
            $table->integer('year');
            $table->integer('digital_number');
            $table->string('name')->default('');
            $table->smallInteger('closed')->default(0);
            $table->string('remark', 1000)->nullable();
            $table->integer('sohead_id')->unsigned()->nullable();

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
        Schema::drop('constructionbidinformations');
    }
}
