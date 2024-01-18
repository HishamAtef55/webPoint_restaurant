<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_section_purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial')->nullable();
            $table->text('note')->nullable();
            $table->integer('branch_id');
            $table->integer('section_id');
            $table->integer('user');
            $table->string('date');
            $table->string('image')->nullable();
            $table->string('type',20)->default('cash');
            $table->integer('supplier');
            $table->double('sub_total',10,3)->nullable();
            $table->double('tax',5,3)->nullable();
            $table->double('tax_value',5,3)->nullable();
            $table->double('discount',5,3)->nullable();
            $table->double('total',10,3)->nullable();
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
        Schema::dropIfExists('section_purchases');
    }
}
