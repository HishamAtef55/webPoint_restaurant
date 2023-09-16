<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersDelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_dels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id');
            $table->integer('dev_id');
            $table->string('table');
            $table->string('op');
            $table->double('sub_total',15, 2)->nullable()->default('0');
            $table->string('user_del');
            $table->integer('branch_id');
            $table->string('t_order', 50);
            $table->date('d_order');
            $table->date('t_delete');
            $table->double('discount')->nullable()->default('0');
            $table->double('details',15, 2)->nullable()->default('0');
            $table->double('extra',15, 2)->nullable()->default('0');
            $table->double('services',15, 2)->nullable()->default('0');
            $table->double('tax',15, 2)->nullable()->nullable();
            $table->string('method', 50)->nullable()->default('0');
            $table->double('total',15, 2)->nullable()->default('0');
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
        Schema::dropIfExists('orders_dels');
    }
}
