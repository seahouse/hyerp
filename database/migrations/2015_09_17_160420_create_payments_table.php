<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('pohead_id');
            $table->decimal('amount', 20, 2)->default(0.0);
            $table->date('paydate')->default(Carbon::now());
            $table->string('notes')->nullable();
            
            $table->timestamps();
            
            $table->foreign('pohead_id')->references('id')->on('poheads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
