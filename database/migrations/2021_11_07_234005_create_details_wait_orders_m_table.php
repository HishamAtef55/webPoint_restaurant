<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsWaitOrdersMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_wait_orders_m', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number_of_order')->default(0);
            $table->integer('detail_id')->default(0);
            $table->double('price', 8, 2)->default(0);
            $table->string('name')->default(0);
            $table->string('wait_order_id')->default(0);
            $table->integer('item_id')->default(0);
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
        Schema::dropIfExists('details_wait_orders_m');
    }
}
