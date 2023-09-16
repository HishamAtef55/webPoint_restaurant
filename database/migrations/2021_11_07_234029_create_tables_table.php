<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('no_of_gest')->default(0);
            $table->integer('state')->default(0);
            $table->integer('booked_up')->default(0);
            $table->integer('circle')->default(0);
            $table->string('number_table')->nullable();
            $table->string('table_id')->default('0');
            $table->integer('hole')->default(0);
            $table->integer('branch_id')->default(0);
            $table->double('top', 8, 2)->default(0.00);
            $table->double('left', 8, 2)->default(0.00);
            $table->double('height', 8, 2)->default(140.00);
            $table->double('width', 8, 2)->default(140.00);
            $table->double('min_charge', 8, 2)->default(0.00);
            $table->integer('guest')->default(1);
            $table->integer('merged')->default(0);
            $table->double('master', 8, 2)->default(0.00);
            $table->string('follow')->default(0);
            $table->timestamps();
            $table->integer('user_id')->nullable();
            $table->string('user')->nullable();
            $table->string('table_open')->nullable();
            $table->integer('printcheck')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
