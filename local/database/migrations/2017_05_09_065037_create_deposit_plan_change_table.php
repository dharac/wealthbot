<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositPlanChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_plan_change', function (Blueprint $table) {
            $table->increments('depo_plan_chng');
            $table->integer('old_planid')->unsigned()->nullable();
            $table->integer('new_planid')->unsigned()->nullable();
            $table->integer('depositid')->unsigned()->nullable();
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
        Schema::dropIfExists('deposit_plan_change');
    }
}
