<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBitcoinPriceDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitcoin_price_deposit', function (Blueprint $table) {
            $table->increments('btcid');
            $table->integer('depositid')->unsigned()->nullable();
            $table->string('status',50)->nullable();
            $table->decimal('bitcoin_in_dollar', 18, 5);
            $table->decimal('amount', 18, 5);
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
        Schema::dropIfExists('bitcoin_price_deposit');
    }
}
