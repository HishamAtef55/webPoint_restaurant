<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseShiftDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_shift_dailies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('date',100)->nullable();
            $table->double('cash',10,2)->nullable();
            $table->double('visa',10,2)->nullable();
            $table->double('hos',10,2)->nullable();
            $table->double('total',10,2)->nullable();
            $table->string('user',100)->nullable();
            $table->integer('shift')->nullable();
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
        Schema::dropIfExists('close_shift_dailies');
    }
}
