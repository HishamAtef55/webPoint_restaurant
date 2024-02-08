<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTableLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_infos', function (Blueprint $table) {
            $table->integer('item_id')->nullable();
            $table->string('op')->nullable();
            $table->double('qty',10,2)->defualt(1)->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('comment')->nullable();
            $table->longtext('extra')->nullable();
            $table->longtext('details')->nullable();
            $table->longtext('without')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_infos', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
}
