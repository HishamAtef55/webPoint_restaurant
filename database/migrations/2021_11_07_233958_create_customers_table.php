<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('location_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('name')->nullable();
            $table->text('location')->nullable();
            $table->text('street')->nullable();
            $table->text('address')->nullable();
            $table->text('role')->nullable();
            $table->text('department')->nullable();
            $table->text('special_marque')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
