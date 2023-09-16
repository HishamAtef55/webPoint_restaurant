<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldWaitOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_wait_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('extra_id')->default(0);
            $table->integer('item_id')->default(0);
            $table->string('date')->default(0);
            $table->string('wait_order_id')->default(0);
            $table->string('number_of_order')->default(0);
            $table->double('price', 8, 2)->default(0);
            $table->double('qty', 8, 2)->default(0);
            $table->string('name')->default(0);
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
        Schema::dropIfExists('sold_wait_orders');
    }
}
