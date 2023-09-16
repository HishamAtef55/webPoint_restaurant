<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CloseShiftGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_shift_group', function (Blueprint $table) {
            $table->integer('close_shift');
            $table->string('name')->nullable();
            $table->double('total',15, 2)->default(0);
            $table->double('quantity',15, 2)->default(0);
            $table->double('total_pre',15, 2)->default(0);
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
        Schema::dropIfExists('close_shift_group');
    }
}
