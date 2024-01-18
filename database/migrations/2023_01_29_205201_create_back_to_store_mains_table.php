<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackToStoreMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_back_to_store_mains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_id',150)->nullable();
            $table->string('order_id',150)->nullable();
            $table->string('date',150);
            $table->string('image',150)->nullable();
            $table->text('note')->nullable();
            $table->integer('store_id');
            $table->integer('branch_id');
            $table->integer('section_id');
            $table->integer('user_id');
            $table->integer('total');
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
        Schema::dropIfExists('back_to_store_mains');
    }
}
