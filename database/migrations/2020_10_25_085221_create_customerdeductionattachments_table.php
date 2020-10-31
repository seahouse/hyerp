<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerdeductionattachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customerdeductionattachments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('customerdeduction_id')->unsigned();
            $table->string('type')->nullable();             // 图片: image, 文件: file
            $table->string('filename')->nullable();         // 文件原名称
            $table->string('path')->nullable();

            $table->timestamps();

            $table->foreign('customerdeduction_id')->references('id')->on('customerdeductions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customerdeductionattachments');
    }
}
