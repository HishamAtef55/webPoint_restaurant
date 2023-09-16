<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->integer('fast_check')->nullable();
            $table->integer('print_invoice')->nullable();
            $table->integer('reservation_receipt')->nullable();
            $table->integer('car_service_receipt')->nullable();
            $table->integer('slip')->nullable();
            $table->string('service_ratio')->nullable();
            $table->string('invoice_copies')->nullable();
            $table->string('tax')->nullable();
            $table->string('printers_input')->nullable();
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
        Schema::dropIfExists('car_services');
    }
}
