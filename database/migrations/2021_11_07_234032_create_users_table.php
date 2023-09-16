<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('mopile')->nullable();
            $table->string('image')->nullable();
            $table->string('email')->unique('users_email_unique');
            $table->integer('branch_id');
            $table->integer('discount_ratio')->nullable();
            $table->integer('job_id');
            $table->double('dialy_salary')->default('0')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('roles_name')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
