<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CloseShift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_shift', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch')->default(0);
            $table->string('date')->default(0);
            $table->string('time')->default(0);
            $table->integer('shift_id')->default(0);
            $table->string('shift')->default(0);
            $table->integer('order_no')->default(0);
            $table->integer('min_order')->default(0);
            $table->integer('max_order')->default(0);
            $table->integer('gust_no')->default(0);
            $table->double('gust_avarge',15, 2)->default(0);
            $table->double('sub_total',15, 2)->default(0);
            $table->double('cash',15, 2)->default(0);
            $table->double('visa',15, 2)->default(0);
            $table->double('hos',15, 2)->default(0);
            $table->double('total_cash',15, 2)->default(0);

            $table->double('table',15, 2)->default(0);
            $table->double('delivery',15, 2)->default(0);
            $table->double('to_go',15, 2)->default(0);

            $table->double('table_ser',15, 2)->default(0);
            $table->double('delivery_ser',15, 2)->default(0);
            $table->double('to_go_ser',15, 2)->default(0);

            $table->double('table_tax',15, 2)->default(0);
            $table->double('delivery_tax',15, 2)->default(0);
            $table->double('to_go_tax',15, 2)->default(0);

            $table->double('table_no',15, 2)->default(0);
            $table->double('delivery_no',15, 2)->default(0);
            $table->double('to_go_no',15, 2)->default(0);

            $table->double('tax',15, 2)->default(0);
            $table->double('service',15, 2)->default(0);
            $table->double('discount',15, 2)->default(0);

            $table->double('details',15, 2)->default(0);
            $table->double('extras',15, 2)->default(0);

            $table->double('tip',15, 2)->default(0);
            $table->double('r_bank',15, 2)->default(0);

            $table->double('customer_payments',15, 2)->default(0);
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
        Schema::dropIfExists('close_shift');
    }
}
