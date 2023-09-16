<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->string('date');
            $table->string('time');
            $table->string('from');
            $table->string('to');
            $table->string('waiter');
            $table->string('type');
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
        Schema::dropIfExists('log_transfers');
    }
}
