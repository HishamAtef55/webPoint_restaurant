<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithoutMaterialsMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('without_materials_m', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('wait_order_id')->default(0);
            $table->string('number_of_order')->default(0);
            $table->integer('material_id')->default(0);
            $table->string('date')->nullable();
            $table->integer('item_id')->default(0);
            $table->double('qty_item',10,2)->default(0);
            $table->double('qty',10,3)->default(0);
            $table->double('price',10,3)->default(0);
            $table->string('name')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('without_materials_m');
    }
}
