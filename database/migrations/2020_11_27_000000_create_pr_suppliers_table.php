<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * 采购申请单和供应商的对应关系表
 */
class CreatePrSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_suppliers', function (Blueprint $table) {
            $table->integer('prhead_id');
            $table->integer('supplier_id')->comment('供应商ID');
            $table->boolean('selected')->default(false)->comment('圈定');
            $table->decimal('amount')->nullable()->comment('总报价');
            $table->string('unit')->default('RMB');
            $table->string('attachments', 4000)->nullable();
            $table->timestamps();

            $table->foreign('prhead_id')->references('id')->on('prheads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_suppliers');
    }
}
