<?php

use App\Models\System\Salarysheet;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ChangeSalarysheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salarysheets', function (Blueprint $table) {
            $table->integer('batch')->nullable()->comment('导入的批次');
        });
        Salarysheet::whereRaw('1=1')->update(['batch' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salarysheets', function (Blueprint $table) {
            $table->dropColumn('batch');
        });
    }
}
