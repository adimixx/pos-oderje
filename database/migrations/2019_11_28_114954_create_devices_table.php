<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('business_id')->nullable();
            $table->integer('merchant_id')->nullable();
            $table->string('uuid');
            $table->unsignedBigInteger('machine_type')->nullable();
            $table->string('name')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->foreign('business_id')->references('b_id')->on('oj_db.business');
            $table->foreign('merchant_id')->references('m_id')->on('oj_db.merchant');
            $table->foreign('machine_type')->references('id')->on('machine_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
