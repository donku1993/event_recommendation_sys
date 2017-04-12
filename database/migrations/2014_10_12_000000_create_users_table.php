<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();

            $table->integer('type')->default(1);
            $table->string('phone', 20);
            $table->integer('address_location')->nullable();
            $table->boolean('gender');
            $table->mediumText('self_introduction')->nullable();
            $table->integer('career');
            $table->integer('year_of_volunteer')->default(0);
            $table->string('icon_image')->default('default.png');
            $table->boolean('allow_email')->default(0);

            $table->json('interest_skills')->nullable();
            $table->json('available_time')->nullable();
            $table->json('available_area')->nullable();

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
