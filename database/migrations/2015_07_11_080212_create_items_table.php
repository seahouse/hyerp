<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('item_number')->unique();
            $table->string('item_name');
            $table->string('item_descrip')->default('');
            $table->integer('itemtype_id')->unsigned();         // 类型：采购、销售、生产等
            $table->integer('itemclass_id')->unsigned();        // 类别
            $table->string('index');                            // 索引，可供快速查找
            $table->decimal('marketprice', 16, 4)->default(0.0);
            
            $table->foreign('itemclass_id')->references('id')->on('itemclasses')->onDelete('cascade');
            $table->foreign('itemtype_id')->references('id')->on('itemtypes')->onDelete('cascade');
            
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
        //$table->dropForeign('items_item_itemclass_id_foreign');
        Schema::drop('items');
    }
}
