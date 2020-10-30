<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ChangeIssuedrawingsTable extends Migration
{
    public function up()
    {
        Schema::table('issuedrawings', function (Blueprint $table) {
            //
            $table->string('company_name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('issuedrawings', function (Blueprint $table) {
            //
            $table->dropColumn('company_name');
        });
    }
}
