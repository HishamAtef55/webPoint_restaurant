<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('chick_name')->nullable();
            $table->string('slep_name')->nullable();
            $table->string('image')->nullable();
            $table->string('unit')->nullable();
            $table->string('printer')->nullable();
            $table->text('note')->nullable();
            $table->double('price')->default('0')->nullable();
            $table->double('takeaway_price')->default('0')->nullable();
            $table->double('dellvery_price')->default('0')->nullable();
            $table->double('cost_price')->default('0')->nullable();
            $table->double('time_during')->default('0')->nullable();
            $table->integer('wight')->default('0')->nullable();
            $table->integer('calories')->default('0')->nullable();
            $table->integer('branch_id');
            $table->integer('menu_id');
            $table->integer('group_id');
            $table->integer('sub_group_id');
            $table->string('active')->nullable();
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
        Schema::dropIfExists('items');
    }
}
