<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceningattachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('epcsecening_id');
            $table->string('type');
            $table->string('filename');
            $table->string('pathname');
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
        Schema::drop('epcseceningattachments');
    }
}
