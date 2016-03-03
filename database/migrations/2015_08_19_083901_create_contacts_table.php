<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            
            $table->boolean('active')->nullable()->default(true);
            $table->string('name')->nullable()->default('');
            $table->integer('addr_id')->nullable();
            $table->string('phone')->nullable()->default('');
            $table->string('phone2')->nullable()->default('');
            $table->string('fax')->nullable()->default('');
            $table->string('email')->nullable()->default('');
            $table->string('remark')->nullable()->default('');
            
            $table->timestamps();

            $table->foreign('addr_id')->references('id')->on('addrs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contacts');
    }
}
