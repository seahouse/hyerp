<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ChangeConstructionbidinformationitemsTable extends Migration
{
    public function up()
    {
        Schema::table('constructionbidinformationitems', function (Blueprint $table) {
            $table->decimal('material_fee')->nullable();
            $table->decimal('install_fee')->nullable();
        });
    }

    public function down()
    {
        Schema::table('constructionbidinformationitems', function (Blueprint $table) {
            $table->dropColumn('material_fee');
            $table->dropColumn('install_fee');
        });
    }
}
