<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailChangeVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_change_verification', function (Blueprint $table) {
            $table->increments('vercod');
            $table->string('new_email')->nullable();
            $table->string('old_email')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->string('status',20)->nullable();
            $table->integer('userid')->unsigned()->nullable();
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
        Schema::dropIfExists('email_change_verification');
    }
}
