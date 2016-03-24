<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReimbursementapprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursementapprovals', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('reimbursement_id');
            $table->integer('level');
            $table->integer('approver_id');
            $table->integer('status');
            $table->string('description');

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
        Schema::drop('reimbursementapprovals');
    }
}
