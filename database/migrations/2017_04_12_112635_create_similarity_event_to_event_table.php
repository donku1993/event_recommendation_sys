<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimilarityEventToEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('similarity_event_to_event', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_one_id')->unsigned();
            $table->integer('event_two_id')->unsigned();
            $table->double('value', 10, 5);

            $table->foreign('event_one_id')->references('id')->on('events');
            $table->foreign('event_two_id')->references('id')->on('events');
            $table->unique(['event_one_id', 'event_two_id']);
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
        Schema::dropIfExists('similarity_event_to_event');
    }
}
