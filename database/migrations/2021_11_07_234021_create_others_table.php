<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('others', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->integer('allow_void')->nullable();
            $table->integer('allow_update')->nullable();
            $table->integer('void_priming')->nullable();
            $table->integer('display_modify')->nullable();
            $table->integer('display_total')->nullable();
            $table->integer('display_waiter')->nullable();
            $table->integer('item_tax')->nullable();
            $table->integer('item_service')->nullable();
            $table->integer('display_addition')->nullable();
            $table->integer('employees_shift')->nullable();
            $table->string('close_day')->nullable();
            $table->integer('time_attendance')->nullable();
            $table->integer('close_day_auto')->nullable();
            $table->integer('close_day_table')->nullable();
            $table->integer('compo')->nullable();
            $table->integer('promotions')->nullable();
            $table->integer('malt_pass_security')->nullable();
            $table->integer('over_sub')->nullable();
            $table->integer('display_visa')->nullable();
            $table->integer('display_ledge')->nullable();
            $table->integer('display_officer')->nullable();
            $table->integer('dis_hospitality')->nullable();
            $table->integer('dis_save')->nullable();
            $table->integer('dis_save_print')->nullable();
            $table->integer('dis_keyboard')->nullable();
            $table->integer('dis_tip_cash')->nullable();
            $table->integer('del_data')->nullable();
            $table->integer('print_reports')->nullable();
            $table->integer('print_void_slip')->nullable();
            $table->integer('collect_items_check')->nullable();
            $table->integer('collect_items_slip')->nullable();
            $table->integer('items_qty')->nullable();
            $table->integer('decimal_qty')->nullable();
            $table->integer('delivery_reciving_customer')->nullable();
            $table->integer('check_balance')->nullable();
            $table->integer('flash_reports')->nullable();
            $table->integer('def_transaction')->nullable();
            $table->integer('expeneses')->nullable();
            $table->integer('copy_invoice')->nullable();
            $table->integer('reservation_copies')->nullable();
            $table->integer('transaction_copies')->nullable();
            $table->integer('fast_checkout')->nullable();
            $table->integer('drawer_printer_check')->nullable();
            $table->string('drawer_printer')->nullable();
            $table->string('transaction_printer')->nullable();
            $table->string('printer')->nullable();
            $table->string('printers_Invoice')->nullable();
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
        Schema::dropIfExists('others');
    }
}
