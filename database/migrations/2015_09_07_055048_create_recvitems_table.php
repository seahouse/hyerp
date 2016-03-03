<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecvitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recvitems', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('orderitem_id');
            $table->integer('recvhead_id')->nullable();     // for future use. forign key shipheads
            $table->decimal('quantity', 18, 6);
            $table->timestampTz('recvdate')->nullable();
            
            $table->timestamps();
            
            $table->index('orderitem_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recvitems');
    }
}
