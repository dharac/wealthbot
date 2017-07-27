<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit', function (Blueprint $table) {
            $table->increments('depositid');
            $table->string('depositno')->nullable();
            $table->integer('planid')->unsigned()->nullable();
            $table->decimal('amount', 18, 5);
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->string('payment_through')->nullable();
            $table->string('currency')->nullable();
            $table->date('maturity_date')->nullable();
            $table->string('transaction_id')->nullable();
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
        Schema::dropIfExists('deposit');
    }
}
