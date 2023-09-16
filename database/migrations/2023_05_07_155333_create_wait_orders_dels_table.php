<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaitOrdersDelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wait_orders_dels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->nullable();
            $table->string('state', 5)->default('0');
            $table->integer('item_id')->default(0);
            $table->string('op')->default('0');
            $table->string('table_id')->default('0');
            $table->integer('sub_num_order')->default(0);
            $table->integer('moved')->default(0);
            $table->string('name')->nullable();
            $table->double('quantity')->default(1);
            $table->double('price')->default(0);
            $table->double('total')->default(0);
            $table->double('total_extra')->default(0);
            $table->double('price_details')->default(0);
            $table->string('discount_name')->default(0);
            $table->string('discount_type')->default(0);
            $table->double('discount')->default(0);
            $table->double('total_discount')->default(0);
            $table->string('comment')->nullable();
            $table->string('without')->nullable();
            $table->double('pick_up')->default(0);
            $table->double('status_take')->default(0);
            $table->double('all_total')->default(0);
            $table->timestamps();
            $table->string('user');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('subgroup_id')->default(0);
            $table->string('subgroup_name', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wait_orders_dels');
    }
}
