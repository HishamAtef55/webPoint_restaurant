<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorePurchasesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_purchases_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->integer('code');
            $table->string('name');
            $table->string('expire')->default(0);
            $table->string('unit')->default(0);
            $table->double('qty',10,3)->default(0);
            $table->double('price',10,3)->default(0);
            $table->double('sub_total',10,3)->default(0);
            $table->double('tax',10,3)->default(0);
            $table->double('discount',10,3)->default(0);
            $table->double('total',10,3)->default(0);
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
        Schema::dropIfExists('store_purchases_details');
    }
}
