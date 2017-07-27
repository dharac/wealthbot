<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpnRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipn_request', function (Blueprint $table) {
            $table->increments('ipnid');
            $table->string('transaction_id')->nullable();
            $table->integer('amount')->unsigned()->nullable();
            $table->text('post_contents')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('ipn_request');
    }
}
