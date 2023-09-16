<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->double('tax', 8, 2)->default(0);
            $table->integer('discount_tax_service')->default(0);
            $table->string('type_ser')->nuallable();
            $table->double('ser_ratio', 8, 2)->nuallable();
            $table->integer('print_slip')->nuallable();
            $table->integer('user_slip')->nuallable();
            $table->integer('print_pilot_slip')->nuallable();
            $table->string('printer')->nuallable();
            $table->integer('pilot_copies')->nuallable();
            $table->integer('Pay_copies')->nuallable();
            $table->integer('print_invoice')->nuallable();
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
        Schema::dropIfExists('delivery');
    }
}
