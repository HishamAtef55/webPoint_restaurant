<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_operations_mains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type',50);
            $table->integer('branch_id')->default('0');
            $table->integer('sec_store');
            $table->integer('code');
            $table->string('date');
            $table->double('price',10,4);
            $table->double('qty',10,4);
            $table->integer('user_id');
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
        Schema::dropIfExists('operations_mains');
    }
}
