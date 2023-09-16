<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_location', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('location')->default(0);
            $table->integer('branch_id')->default(0);
            $table->double('price')->default(0);
            $table->double('pilot_value')->default(0);
            $table->string('time', 100)->nullable(0);
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
        Schema::dropIfExists('customer_location');
    }
}
