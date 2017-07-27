<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_amount_in_out', function (Blueprint $table) {
            $table->increments('wallid');
            $table->decimal('amount', 18,5);
            $table->integer('depositid')->unsigned()->nullable();
            $table->string('deposit_type',20)->nullable();
            $table->integer('redepositid')->unsigned()->nullable();
            $table->string('status',255)->nullable();
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
        Schema::dropIfExists('wallet_amount_in_out');
    }
}
