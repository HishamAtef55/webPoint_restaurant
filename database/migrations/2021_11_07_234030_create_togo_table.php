<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTogoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('togo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->integer('print_slip')->nullable();
            $table->integer('print_togo')->nullable();
            $table->integer('display_checkout_screen')->nullable();
            $table->integer('print_reservation_receipt')->nullable();
            $table->integer('print_invice')->nullable();
            $table->integer('fast_check')->nullable();
            $table->integer('convert_togo_table')->nullable();
            $table->string('invoice_copies')->nullable();
            $table->string('service_ratio')->nullable();
            $table->string('tax')->nullable();
            $table->integer('discount_tax_service')->default(0);
            $table->string('printer')->nullable();
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
        Schema::dropIfExists('togo');
    }
}
