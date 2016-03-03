<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateReceiptpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiptpayments', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('sohead_id');
            $table->decimal('amount', 20, 2)->default(0.0);
            $table->date('recvdate')->default(Carbon::now());
            $table->string('notes')->nullable();
            
            $table->timestamps();
            
            $table->foreign('sohead_id')->references('id')->on('soheads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('receiptpayments');
    }
}
