<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterestPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interest_payment', function (Blueprint $table) {
            $table->increments('int_proid');
            $table->integer('planid')->unsigned();
            $table->decimal('profit', 18, 5);
            $table->decimal('amount', 18, 5);
            $table->decimal('pro_amount', 18, 5);
            $table->integer('depositid')->unsigned()->nullable();
            $table->string('status')->nullable();
            $table->string('intno')->nullable();
            $table->string('interest_type',20)->nullable();
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
        Schema::dropIfExists('interest_payment');
    }
}
