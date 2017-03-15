<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('signUpEndDate');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
            $table->integer('numberOfPeople')->unsigned();
            $table->string('previewImage')->default('old.jpg');
            $table->string('title');
            $table->mediumText('content');
            $table->integer('location');
            $table->integer('type');
            $table->mediumText('schedule')->nullable();
            $table->mediumText('requirement')->nullable();
            $table->mediumText('remark')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->json('bonus_skills')->nullable();
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
        Schema::dropIfExists('events');
    }
}
