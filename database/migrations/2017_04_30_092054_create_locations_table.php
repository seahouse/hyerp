<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('warehouse_id');
            $table->boolean('active')->default(true);
            $table->boolean('restricted')->default(false);      // 被限制的，如果为true，可以设置该位置的限制商品
            $table->text('descrip')->nullable();
            $table->string('zone')->nullable();                     // 区域
            $table->string('aisle')->nullable();                    // 行
            $table->string('rack')->nullable();                     // 架
            $table->string('bin')->nullable();                      // 箱
            $table->string('locationname')->nullable();                 // 位，货位
            $table->string('formatname')->nullable();

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
        Schema::drop('locations');
    }
}
