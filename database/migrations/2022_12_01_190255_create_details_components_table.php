<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_details_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->integer('main_id');
            $table->integer('item');
            $table->integer('details');
            $table->integer('material_id');
            $table->string('material_name');
            $table->double('cost',10,3)->default(0.00);
            $table->double('quantity',10,3)->default(0.00);
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
        Schema::dropIfExists('stock_details_components');
    }
}
