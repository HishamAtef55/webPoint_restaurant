<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_material_recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('main_id');
            $table->integer('material_id');
            $table->string('material_name');
            $table->double('quantity',10,3);
            $table->double('cost',10,3);
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
        Schema::dropIfExists('material_recipes');
    }
}
