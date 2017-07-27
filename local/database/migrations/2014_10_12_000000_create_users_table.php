<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username',150)->unique();
            $table->string('referral')->nullable();
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('email',150)->unique();
            $table->string('phone',20)->nullable();
            $table->string('istype',20)->nullable();
            $table->string('password');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('coucod')->unsigned();
            $table->string('zip',10)->nullable();
            $table->string('bitcoin_id')->nullable();
            $table->integer('sec_question')->unsigned()->nullable();
            $table->integer('terms')->unsigned()->nullable();
            $table->string('sec_answer')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->integer('founder')->unsigned()->default('0');
            $table->string('confirmation_code')->nullable();
            $table->string('getresponseid')->nullable();
            $table->enum('status', ['active', 'locked','suspended']);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('modified_by')->unsigned()->nullable();
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
