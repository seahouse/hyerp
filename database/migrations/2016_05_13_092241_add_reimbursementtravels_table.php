<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReimbursementtravelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursementtravels', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('reimbursement_id')->nullable();
            $table->date('datego');
            $table->date('dateback');
            $table->integer('customer_id')->nullable();             // 客户id
            $table->string('contacts')->nullable();                 // 客户联系人
            $table->string('contactspost')->nullable();             // 客户联系人职务
            $table->integer('order_id')->nullable();                // 对应订单id
            $table->string('descrip')->nullable();              
			$table->integer('seq')->default(0);

            $table->timestamps();

            $table->foreign('reimbursement_id')->references('id')->on('reimbursements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reimbursementtravels');
    }
}
