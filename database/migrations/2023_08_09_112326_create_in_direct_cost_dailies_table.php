<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInDirectCostDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_in_direct_cost_dailies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cost_id')->references('id')->on('stock_in_direct_costs')->cascadeOnDelete();
            $table->integer('user_id');
            $table->string('date');
            $table->string('month');
            $table->double('value',10,2)->default(0);
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
        Schema::dropIfExists('stock_in_direct_cost_dailies');
    }
}
