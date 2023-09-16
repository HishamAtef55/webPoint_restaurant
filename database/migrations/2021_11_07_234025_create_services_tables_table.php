<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->integer('fast_checkout')->nullable();
            $table->integer('print_invoic')->nullable();
            $table->integer('reser_recipt')->nullable();
            $table->integer('invoice_payment')->nullable();
            $table->integer('payment_teble')->nullable();
            $table->integer('invoic_teble')->nullable();
            $table->integer('end_teble')->nullable();
            $table->integer('vou_copon')->nullable();
            $table->integer('mincharge_screen')->nullable();
            $table->integer('display_table')->nullable();
            $table->integer('receipt_checkout')->nullable();
            $table->integer('receipt_send')->nullable();
            $table->integer('slip_all')->nullable();
            $table->integer('slip_copy')->nullable();
            $table->integer('pr_reservation')->nullable();
            $table->integer('car_receipt')->nullable();
            $table->integer('print_slip')->nullable();
            $table->double('tax_service')->nullable();
            $table->double('r_bank')->default('0')->nullable();
            $table->string('printers_input')->nullable();
            $table->string('printer_shift')->nullable();
            $table->string('invoic_copies')->nullable();
            $table->string('min_charge')->nullable();
            $table->double('service_ratio')->nullable();
            $table->double('tax')->nullable();
            $table->integer('discount_tax_service')->default(0)->nullable();
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
        Schema::dropIfExists('services_tables');
    }
}
