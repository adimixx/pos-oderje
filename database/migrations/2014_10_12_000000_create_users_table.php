<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name')->nullable();
            $table->integer('business_id')->nullable();
            $table->integer('merchant_id')->nullable();
            $table->integer('created_by');
            $table->integer('ojdb_pruser')->nullable();
            $table->string('status')->nullable();
            $table->string('api_token',80)->after('password')->unique()->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('ojdb_pruser')->references('u_id')->on('v2tpdev.PRUSER');
            $table->foreign('business_id')->references('b_id')->on('oj_db.business');
            $table->foreign('merchant_id')->references('m_id')->on('oj_db.merchant');
            $table->foreign('created_by')->references('u_id')->on('v2tpdev.PRUSER');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
