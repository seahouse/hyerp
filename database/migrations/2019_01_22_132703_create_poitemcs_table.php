<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoitemcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poitemcs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('poheadc_id')->nullable();
            $table->integer('fabric_sequence_no');
            $table->decimal('quantity', 12)->default(0.0);
            $table->string('unit');
            $table->string('material_code');
            $table->string('fabric_width');
            $table->string('fabric_description');
            $table->string('shrinkage');
            $table->string('hand_feel');
            $table->string('for_washing');
            $table->string('for_crocking');
            $table->string('yarn_count');
            $table->string('construction');
            $table->string('IO_description');
            $table->decimal('positive_percentage_tolerance');
            $table->decimal('negative_percentage_tolerance');
            $table->string('transportation_method_type_code');      // A: Air; S: Sea; L: Land
            $table->string('sample_description')->default('');
            $table->string('prodn_description')->default('');
            $table->string('color_sequence');
            $table->decimal('unit_price');
            $table->string('color_desc1')->default('');
            $table->string('color_desc2')->default('');
            $table->string('color_desc3')->default('');
            $table->string('color_desc4')->default('');
            $table->string('color_desc5')->default('');
            $table->string('quantity_per_color');
            $table->date('shipment_date');

            $table->timestamps();

            $table->foreign('poheadc_id')->references('id')->on('poheadcs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('poitemcs');
    }
}
