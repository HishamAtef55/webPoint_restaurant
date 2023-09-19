<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default(0)->nullable();
            $table->string('chick_name')->default(0)->nullable();
            $table->string('slep_name')->default(0)->nullable();
            $table->string('barcode')->default(0)->nullable();
            $table->string('unit')->default(0)->nullable();
            $table->string('image')->default(0)->nullable();
            $table->string('printer')->default(0)->nullable();
            $table->double('price', 8, 2)->default(0)->nullable();
            $table->double('cost_price', 8, 2)->default(0)->nullable();
            $table->double('time_during')->default(0)->nullable();
            $table->double('wight', 8, 2)->default(0)->nullable();
            $table->integer('calories')->default(0)->nullable();
            $table->integer('branch_id')->default(0)->nullable();
            $table->integer('menu_id')->default(0)->nullable();
            $table->integer('group_id')->default(0)->nullable();
            $table->integer('sub_group_id')->default(0)->nullable();
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
        Schema::dropIfExists('extras');
    }
}
