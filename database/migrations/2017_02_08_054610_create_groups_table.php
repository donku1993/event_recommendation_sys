<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('registered_id');
            $table->string('registered_file')->default('');
            $table->string('icon_image')->default('default.png');
            $table->date('establishment_date');
            $table->string('principal_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->mediumText('introduction')->nullable();
            $table->json('activity_area');
            $table->tinyInteger('status')->default(0);
            $table->string('remark')->nullable();
            $table->boolean('show')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
