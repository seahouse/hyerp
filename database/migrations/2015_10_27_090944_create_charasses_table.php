<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charasses', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('target_type');
            $table->integer('target_id');
            $table->integer('char_id');
            $table->string('value');
            $table->boolean('default')->default(false);
            $table->decimal('price', 16, 4)->default(0);
            
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
        Schema::drop('charasses');
    }
}
