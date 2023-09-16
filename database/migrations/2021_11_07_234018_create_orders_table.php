<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->integer('serial_shift')->nullable()->default('0');
            $table->integer('dev_id');
            $table->string('table');
            $table->string('op');
            $table->string('state', 2);
            $table->integer('shift_status')->nullable()->default('1');
            $table->double('sub_total',15, 2)->nullable()->default('0');
            $table->double('delivery',15, 2)->nullable()->default('0');
            $table->string('user');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('customer_id')->nullable()->default('0');
            $table->string('customer_name')->nullable()->default('0');
            $table->string('t_order', 50);
            $table->date('d_order');
            $table->integer('take_order')->nullable()->default('0');
            $table->integer('delivery_order')->nullable()->default('0');
            $table->integer('to_pilot')->nullable()->default('0');
            $table->integer('pilot_account')->nullable()->default('0');
            $table->integer('hold_list')->nullable()->default('0');
            $table->string('time_hold_list', 50)->nullable();
            $table->string('date_holde_list')->nullable()->default('0');
            $table->integer('pilot_id')->nullable()->default('0');
            $table->string('pilot_name', 150)->nullable()->default('0');
            $table->string('location')->nullable()->default('0');
            $table->double('discount')->nullable()->default('0');
            $table->string('discount_name', 150)->nullable()->default('0');
            $table->string('discount_type', 50)->nullable()->default('0');
            $table->double('total_discount',15, 2)->nullable()->default('0');
            $table->double('total_details',15, 2)->nullable()->default('0');
            $table->double('total_extra',15, 2)->nullable()->default('0');
            $table->double('total',15, 2)->nullable()->default('0');
            $table->string('shift', 100)->nullable()->default('0');
            $table->string('cashier', 100)->nullable()->default('0');
            $table->double('services',15, 2)->nullable()->default('0');
            $table->double('service_ratio',15, 2)->nullable()->default('0');
            $table->integer('state_service')->nullable()->default('0');
            $table->double('tax',15, 2)->nullable()->nullable();
            $table->double('tax_ratio',15, 2)->nullable()->nullable();
            $table->integer('state_tax')->nullable()->default(0);
            $table->integer('discount_tax_service')->nullable()->default(0);
            $table->double('min_charge',15, 2)->nullable()->default('0');
            $table->integer('gust')->nullable()->default(1);
            $table->string('method', 50)->nullable()->default('0');
            $table->integer('no_print')->nullable()->default('0');
            $table->double('tip',15, 2)->nullable()->default('0');
            $table->double('cash',15, 2)->nullable()->default('0');
            $table->double('visa',15, 2)->nullable()->default('0');
            $table->integer('hos')->nullable()->default('0');
            $table->double('r_bank',15, 2)->nullable()->default('0');
            $table->integer('devcashier')->nullable()->default('0');
            $table->string('t_closeorder', 50)->nullable()->default('0');
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
        Schema::dropIfExists('orders');
    }
}
