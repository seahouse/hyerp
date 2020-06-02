<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstructionbidinformationitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constructionbidinformationitems', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('constructionbidinformation_id')->unsigned();
            $table->string('projecttype');
            $table->string('key');
            $table->string('purchaser')->nullable();
            $table->string('specification_technicalrequirements')->nullable();
            $table->decimal('value')->nullable();
            $table->decimal('multiple')->nullable();
//            $table->decimal('value_line3')->nullable();
//            $table->decimal('value_line4')->nullable();
            $table->string('unit')->nullable();
            $table->string('remark', 1000)->nullable();
            $table->integer('sort');
//            $table->integer('type');

            $table->timestamps();

            $table->foreign('constructionbidinformation_id')->references('id')->on('constructionbidinformations')->onDelete('cascade');

            $table->index('constructionbidinformation_id', 'key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('constructionbidinformationitems');
    }
}
