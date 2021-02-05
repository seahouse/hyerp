<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeDeptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('depts', function (Blueprint $table) {
            // 需手动将id列修改为如下规则
            // $table->integer('id')->unique();
            $table->boolean('auto_add_user')->nullable();
            $table->boolean('create_dept_group')->nullable();
            $table->json('ext')->nullable();
            $table->integer('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'depts',
            function (Blueprint $table) {
                $table->dropColumn('auto_add_user');
                $table->dropColumn('create_dept_group');
                $table->dropColumn('ext');
                $table->dropColumn('parent_id');
            }
        );
    }
}
