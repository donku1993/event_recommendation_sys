<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsEventsRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_events_relation', function (Blueprint $table) {
            $table->integer('group_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->boolean('main');

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('event_id')->references('id')->on('events');
            $table->unique(['group_id', 'event_id']);
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
        Schema::dropIfExists('groups_events_relation');
    }
}
