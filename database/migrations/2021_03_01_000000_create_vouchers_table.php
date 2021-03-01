<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voucher_no')->comment('到账凭证单号')->unique();
            $table->date('post_date')->useCurrent()->comment('到账日期');
            $table->string('remark')->comment('备注')->nullable();
            $table->string('ref_id')->comment('采购订单ID')->nullable();
            $table->string('ref_type')->default('PO');
            $table->integer('creator')->comment('创建人');
            $table->integer('updater')->comment('修改人')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
}
