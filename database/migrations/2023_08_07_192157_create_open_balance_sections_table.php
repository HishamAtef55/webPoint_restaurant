<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenBalanceSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_open_balance_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('material_id');
            $table->string('date',30);
            $table->integer('branch');
            $table->integer('section');
            $table->float('unit_price',10,3);
            $table->float('open_value',10,3);
            $table->float('qty',10,3);
            $table->float('close_value',10,3);
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
        Schema::dropIfExists('open_balance_sections');
    }
}
