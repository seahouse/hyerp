<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstructionbidinformationfieldtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constructionbidinformationfieldtypes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('constructionbidinformation_id')->unsigned();
            $table->string('constructionbidinformation_fieldtype');

            $table->timestamps();

            $table->foreign('constructionbidinformation_id')->references('id')->on('constructionbidinformations')->onDelete('cascade');
            $table->unique(['constructionbidinformation_id', 'constructionbidinformation_fieldtype']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('constructionbidinformationfieldtypes');
    }
}
