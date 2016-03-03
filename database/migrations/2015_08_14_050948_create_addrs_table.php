<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addrs', function (Blueprint $table) {
            $table->increments('id');
            
            $table->boolean('active')->nullable()->default(true);
            $table->integer('country_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('county_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('line1')->nullable()->default('');
            $table->string('line2')->nullable()->default('');
            $table->string('postalcode')->nullable()->default('');
            $table->string('remark')->nullable()->default('');
            
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countrys');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('citys');
            $table->foreign('county_id')->references('id')->on('countys');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addrs');
    }
}
