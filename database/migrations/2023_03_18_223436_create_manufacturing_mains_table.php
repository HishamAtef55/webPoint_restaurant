<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturingMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturing_mains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('date')->nullable();
            $table->text('type',50)->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('sec_store')->nullable();
            $table->integer('code')->nullable();
            $table->float('halk',10,3)->nullable();
            $table->float('price',10,3)->nullable();
            $table->float('qty',10,3)->nullable();
            $table->float('total',10,3)->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('manufacturing_mains');
    }
}
