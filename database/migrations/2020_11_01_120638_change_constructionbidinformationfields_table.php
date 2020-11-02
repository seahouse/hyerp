<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ChangeConstructionbidinformationfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constructionbidinformationfields', function (Blueprint $table) {
            $table->decimal('unitprice_bidder', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constructionbidinformationfields', function (Blueprint $table) {
            $table->dropColumn('unitprice_bidder');
        });
    }
}
