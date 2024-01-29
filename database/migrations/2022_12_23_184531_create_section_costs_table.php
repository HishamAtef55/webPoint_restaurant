<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_section_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id');
            $table->integer('section_id');
            $table->integer('code');
            $table->string('unit');
            $table->string('material');
            $table->double('qty',10,3)->default(0);
            $table->double('f_price',10,3)->default(0);
            $table->double('average',10,3)->default(0);
            $table->double('l_price',10,3)->default(0);
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
        Schema::dropIfExists('stock_section_costs');
    }
}
