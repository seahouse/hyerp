<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpcseceningmaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epcseceningmaterials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('epcseceningcranes_id')->nullable();

            $table->string('material_type')->nullable();
            $table->string('materialname')->nullable();
            $table->string('specification')->nullable();
            $table->string('price_unit')->nullable();
            $table->string('specification')->nullable();
            $table->decimal('number',8,2)->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->decimal('total',8,2)->nullable();
            $table->string('remark')->nullable();
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
        Schema::drop('epcseceningmaterials');
    }
}
