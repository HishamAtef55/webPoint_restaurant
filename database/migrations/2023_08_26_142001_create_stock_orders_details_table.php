<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOrdersDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_orders_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->references('id')->on('stock_orders')->cascadeOnDelete();
            $table->integer('code');
            $table->string('name');
            $table->string('unit');
            $table->double('qty',10,3)->default(0);
            $table->double('price',10,3)->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('stock_orders_details');
    }
}
