<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('main_group');
            $table->integer('sub_group');
            $table->integer('code');
            $table->string('name');
            $table->double('cost')->nullable()->default(0);
            $table->double('price')->nullable()->default(0);
            $table->string('unit')->nullable()->default(0);
            $table->double('loss')->nullable()->default(0);
            $table->double('min_store')->nullable()->default(0);
            $table->double('max_store')->nullable()->default(0);
            $table->double('min_section')->nullable()->default(0);
            $table->double('max_section')->nullable()->default(0);
            $table->string('storage');
            $table->integer('expire')->nullable();
            $table->integer('gard')->nullable();
            $table->integer('all_group')->nullable()->default(0);
            $table->integer('manfu')->nullable()->default(0);
            $table->integer('packing')->nullable()->default(0);
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
        Schema::dropIfExists('stock_materials');
    }
}
