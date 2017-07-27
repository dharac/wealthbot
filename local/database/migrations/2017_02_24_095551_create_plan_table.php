<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_m', function (Blueprint $table) {
            $table->increments('planid');
            $table->string('plan_name');
            $table->decimal('spend_min_amount', 18, 5);
            $table->decimal('spend_max_amount', 18, 5);
            $table->decimal('profit', 18, 5);
            $table->integer('interest_period_type')->unsigned()->nullable();
            $table->integer('plan_status')->unsigned()->nullable();
            $table->integer('nature_of_plan')->unsigned()->nullable();
            $table->integer('founder')->unsigned()->default('0');
            $table->integer('new_founder')->unsigned()->default('0');
            $table->string('duration')->nullable();
            $table->integer('duration_time')->unsigned()->nullable();
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
        Schema::dropIfExists('plan_m');
    }
}
