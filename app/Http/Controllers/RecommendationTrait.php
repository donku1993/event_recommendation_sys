<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Record;

trait RecommendationTrait
{
	public function alsoViewEvent(int $id)
	{
		$numberOfEvents = 4;

		// get the users who have view this event
		$users = Record::usersByEventID($id)->toArray();
		// count the users viewing record
		$events_count = Record::eventCountByUserArray($users);
		// calculate the average
		$average = $events_count->sum('count') / $events_count->count();
		// filter out the events that the number of view is lower that the average
		$events_after_filter = $events_count->filter(function ($value, $key) use ($average, $id) {
			return ($value['count'] > $average) && ($value['event_id'] !== $id);
		});
		// out to array format
		$final_event_id_list = $events_after_filter->pluck('event_id')->toArray();

		// get the events
		$events = Event::whereIn('id', $final_event_id_list)->inRandomOrder()->get();
		// get the events that can be join
		$events = Event::getJoinable($events)->take($numberOfEvents);

		// add some random events if there are not enough result
		if ($events->count() < $numberOfEvents)
		{
			$miss = $numberOfEvents - $events->count();

			$all_events = Event::whereNotIn('id', $final_event_id_list)->inRandomOrder()->get();
			$all_events = Event::getJoinable($all_events)->take($miss);

			$events = $events->merge($all_events);
		}

		return $events;
	}
}