<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id')->default(0);
            $table->integer('detail_id');
            $table->double('price', 8, 2)->default(0.00);
            $table->string('section')->nullable();
            $table->integer('max')->default(1);
            $table->integer('branch_id');
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
        Schema::dropIfExists('details_items');
    }
}
