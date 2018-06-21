<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('module')->default('');
            $table->string('titleshow')->nullable();
            $table->smallInteger('active')->default(1);
            $table->smallInteger('autostatistics')->default(1);
            $table->string('descrip')->default('')->commet('描述');
            $table->string('statement', 1023)->default('')->commet('SQL语句');

            $table->timestamps();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reports');
    }
}
