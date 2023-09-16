<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsSubGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details_sub_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sub_id')->default(0);
            $table->integer('detail_id')->default(0);
            $table->double('price', 8, 2)->default(0.00);
            $table->string('section')->nullable();
            $table->integer('max')->default(0);
            $table->timestamps();
            $table->integer('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details_sub_group');
    }
}
