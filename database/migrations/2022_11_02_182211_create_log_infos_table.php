<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch');
            $table->string('user');
            $table->bigInteger('order')->nullable();
            $table->string('Table')->nullable();
            $table->string('item')->nullable();
            $table->string('type');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('log_infos');
    }
}
