<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGetresponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('getresponse', function (Blueprint $table) {
            $table->increments('getresid');
            $table->string('username',255)->nullable();
            $table->string('bcc',255)->nullable();
            $table->string('campaignId',255)->nullable();
            $table->string('fromFieldId',255)->nullable();
            $table->string('getresponse_api_key',255)->nullable();
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
        Schema::dropIfExists('getresponse');
    }
}
