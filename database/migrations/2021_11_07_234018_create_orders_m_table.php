<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersMTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_m', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->integer('serial_shift')->nullable();
            $table->integer('dev_id')->nullable();
            $table->string('table')->nullable();
            $table->string('op')->nullable();
            $table->string('state', 2)->nullable();
            $table->double('sub_total',15, 2)->nullable();
            $table->double('delivery',15, 2)->nullable();
            $table->timestamps();
            $table->string('user')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('t_order', 50);
            $table->string('d_order')->nullable();
            $table->integer('take_order')->nullable();
            $table->integer('delivery_order')->nullable();
            $table->integer('to_pilot')->nullable();
            $table->integer('pilot_account')->nullable();
            $table->integer('hold_list')->nullable();
            $table->string('time_hold_list', 50)->nullable();
            $table->string('date_holde_list')->nullable();
            $table->integer('pilot_id')->nullable();
            $table->string('pilot_name', 150)->nullable();
            $table->text('location')->nullable();
            $table->double('discount')->nullable();
            $table->string('discount_name', 150)->nullable();
            $table->string('discount_type', 50)->nullable();
            $table->double('total_discount',15, 2)->nullable();
            $table->double('total_details',15, 2)->nullable();
            $table->double('total_extra',15, 2)->nullable();
            $table->double('total',15, 2)->nullable();
            $table->string('shift', 100)->nullable();
            $table->string('cashier', 100)->nullable();
            $table->double('services',15, 2)->nullable();
            $table->double('service_ratio',15, 2)->nullable();
            $table->integer('state_service')->nullable();
            $table->double('tax',15, 2)->nullable();
            $table->double('tax_ratio',15, 2)->nullable();
            $table->integer('state_tax')->nullable();
            $table->integer('discount_tax_service')->nullable();
            $table->double('min_charge',15, 2)->nullable();
            $table->integer('gust')->nullable();
            $table->string('method', 50)->nullable();
            $table->integer('no_print')->nullable();
            $table->double('tip',15, 2)->nullable();
            $table->double('cash',15, 2)->nullable();
            $table->double('visa',15, 2)->nullable();
            $table->integer('hos')->nullable();
            $table->double('r_bank',15, 2)->nullable();
            $table->integer('devcashier')->nullable();
            $table->string('t_closeorder', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_m');
    }
}
