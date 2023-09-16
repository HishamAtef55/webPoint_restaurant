<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->string('table_id');
            $table->string('customer');
            $table->string('phone');
            $table->float('cash');
            $table->date('date');
            $table->string('time_from');
            $table->timestamps();
            $table->integer('user_id');
            $table->string('user');
            $table->integer('status')->default(0);
            $table->integer('user_del')->nullable();
            $table->string('time_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation');
    }
}
