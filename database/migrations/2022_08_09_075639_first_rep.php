<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FirstRep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('first_rep', function (Blueprint $table) {
            $table->integer('id')->nullable();
            $table->integer('id_of_wait')->default(0);
            $table->string('date_of_order')->nullable();
            $table->string('t_order')->nullable();
            $table->string('name')->nullable();
            $table->double('quantity')->nullable();
            $table->string('type')->nullable();
            $table->string('waiter_name')->nullable();
            $table->integer('item_id')->default(0);
            $table->string('printer_name')->nullable();
            $table->integer('serial_shift')->default(0);
            $table->string('type_of_print')->nullable();
            $table->integer('shift')->default(0);

            $table->string('op')->default(0);
            $table->string('table');
            $table->string('end_time')->nullable();

            $table->float('price')->default(0);
            $table->float('tottal')->default(0);
            $table->string('start_time');
            $table->float('sub_total')->default(0);
            $table->float('vat')->default(0);
            $table->float('servic')->default(0);
            $table->float('Disco')->default(0);
            $table->float('cash')->default(0);
            $table->float('visa')->default(0);
            $table->integer('gust')->default(0);
            $table->string('method')->nullable();
            $table->float('min_charge')->default(0);
            $table->string('notes')->nullable();
            $table->integer('cust_id')->default(0);

            $table->string('cust_name');
            $table->string('location');
            $table->string('street');
            $table->string('address');
            $table->string('role');
            $table->string('department');
            $table->string('special_marque');
            $table->string('notes_of_client');
            $table->string('poilt_name');
            $table->string('customer_phone');
            $table->timestamp('created_at')->default('2022-04-30 21:52:34');
            $table->timestamp('updated_at')->default('2022-04-30 21:52:34');
            $table->string('pick_up')->default(9);
            $table->string('ra_bank')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('first_rep');
    }
}
