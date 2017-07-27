<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevelCommisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('level_commisions', function (Blueprint $table) {
            $table->increments('comid');
            $table->integer('depositid')->unsigned()->nullable();
            $table->decimal('interest', 18, 5);
            $table->decimal('com_rate', 18, 5);
            $table->decimal('commission', 18, 5);
            $table->string('commission_type',20)->nullable();
            $table->integer('referral_level')->unsigned()->nullable();
            $table->integer('downlineid')->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->string('comno')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('modified_by')->unsigned()->nullable();
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
        Schema::dropIfExists('level_commisions');
    }
}
