<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendordeductionattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendordeductionattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('vendordeduction_id');
            $table->string('type')->nullable();             // 文件: file, 图片: image,
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('vendordeduction_id')->references('id')->on('vendordeductions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vendordeductionattachments');
    }
}
