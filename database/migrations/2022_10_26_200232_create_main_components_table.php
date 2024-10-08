<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_main_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('cost',10,3);
            $table->double('percentage',10,3);
            $table->double('quantity',10,3);
            $table->integer('item');
            $table->integer('branch');
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
        Schema::dropIfExists('stock_main_components');
    }
}
