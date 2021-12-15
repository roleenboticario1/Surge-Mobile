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
            $table->id();
            $table->string('lastname');
            $table->string('firstname');
            $table->date('date_of_birth');
            $table->string('email')->unique();
            $table->bigInteger('mobile_number');
            $table->integer('house_number');
            $table->string('street');
            $table->string('city');
            $table->string('country');
            $table->string('province');
            $table->text('learned_about_serge_mobile');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('email_verified')->default(0);
            $table->string('email_verification_token');
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
