<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('void', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->nullable();
            $table->string('date')->nullable();
            $table->string('state', 5)->default('0');
            $table->integer('item_id')->default(0);
            $table->string('op')->default('0');
            $table->string('table_id')->default('0');
            $table->integer('sub_num_order')->default(0);
            $table->integer('moved')->default(0);
            $table->string('name')->nullable();
            $table->double('quantity')->default(1);
            $table->double('price')->nullable()->default(0);
            $table->double('total')->nullable()->default(0);
            $table->double('total_extra')->default(0);
            $table->double('price_details')->default(0);
            $table->string('discount_name')->nullable();
            $table->string('discount_type')->nullable();
            $table->double('discount')->default(0);
            $table->double('total_discount')->default(0);
            $table->string('comment')->nullable();
            $table->string('without')->nullable();
            $table->double('pick_up')->default(0);
            $table->timestamps();
            $table->string('user');
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('subgroup_id')->nullable();
            $table->string('subgroup_name', 50);
            $table->string('status', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('void');
    }
}
