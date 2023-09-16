<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers_mains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_id',100)->nullable();
            $table->string('date',150);
            $table->string('image',150)->nullable();
            $table->text('note')->nullable();
            $table->string('type',30);
            $table->integer('branch_id')->nullable();
            $table->integer('from');
            $table->integer('to');
            $table->integer('user_id');
            $table->integer('total');
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
        Schema::dropIfExists('transfers_mains');
    }
}
