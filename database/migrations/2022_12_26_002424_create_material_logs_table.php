<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('code');
            $table->text('user');
            $table->text('type');
            $table->text('from')->nullable();
            $table->text('to')->nullable();
            $table->text('store')->nullable();
            $table->text('order_id')->nullable();
            $table->text('section')->nullable();
            $table->double('qty')->nullable()->default(0);
            $table->text('unit')->nullable();
            $table->integer('invoice_id')->nullable()->default(0);
            $table->double('price')->nullable()->default(0);
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
        Schema::dropIfExists('material_logs');
    }
}
