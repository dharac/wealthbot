<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinpaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coinpayment', function (Blueprint $table) {
            $table->increments('coinid');
            $table->string('merchant_id');
            $table->string('public_id');
            $table->string('private_id');
            $table->string('ipn_secret')->nullable();
            $table->string('ipn_email')->nullable();
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('coinpayment');
    }
}
