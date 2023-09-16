<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPrintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_print', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch');
            $table->string('order_id')->nullable();
            $table->integer('type');
            $table->string('val_type')->nullable();
            $table->double('quan')->default(0);
            $table->integer('no_copies')->nullable();
            $table->string('printer')->nullable();
            $table->integer('item')->nullable();
            $table->integer('clos_day')->nullable();
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
        Schema::dropIfExists('order_print');
    }
}
