<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHalkItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_halk_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch');
            $table->integer('section_id');
            $table->integer('item_id');
            $table->string('item');
            $table->double('qty',10,2);
            $table->integer('user_id');
            $table->string('date');
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
        Schema::dropIfExists('stock_halk_items');
    }
}
