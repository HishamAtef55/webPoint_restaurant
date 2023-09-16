<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainMaterialRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_material_recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('material');
            $table->double('quantity',10,3);
            $table->double('cost',10,3);
            $table->double('percentage',10,3);
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
        Schema::dropIfExists('main_material_recipes');
    }
}
