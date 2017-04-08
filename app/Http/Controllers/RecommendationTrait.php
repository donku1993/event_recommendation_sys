<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Record;
use App\Models\Similarity;

trait RecommendationTrait
{
	public function similarityCalculation_user_given(int $user_id)
	{

	}

	public function similarityCalculation_event_given(int $event_id)
	{

	}

	public function recommendation_user_given(int $user_id)
	{
		$numberOfEvents = 4;

		// get all the similarity value of the user to all events
		$similarities = Similarity::where('user_id', $user_id)->get();
		// check
		if ($similarities->count() == 0 || is_null(User::find($user_id)))
		{
			return $this->randomJoinableEvents($numberOfEvents);
		}
		// calculate the average
		$average = $similarities->sum('value') / $similarities->count();
		// filter out the events that the value is lower than the average
		$events_after_filter = $similarities->filter(function ($value, $key) use ($average) {
			return ($value->value > $average);
		});
		// output to array format
		$final_event_id_list = $events_after_filter->pluck('event_id')->toArray();

		// get the events that can be join
		$events = Event::whereIn('id', $final_event_id_list)->inRandomOrder()->get();
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

	public function recommendation_event_given(int $event_id)
	{
		$event = Event::find($event_id);

		// check event id
		if (is_null($event))
		{
			return [];
		}

		$numberOfUsers = floor($event->numberOfPeople * 1.2);

		// get all the similarity value of the event to all users
		$similarities = Similarity::where('event_id', $event_id)->get();
		// check similarity values
		if ($similarities->count() == 0)
		{
			return $this->randomNormalUsers($numberOfUsers);
		}
		// calculate the average
		$average = $similarities->sum('value') / $similarities->count();
		// filter out the users that the value is lower than the average
		$users_after_filter = $similarities->filter(function ($value, $key) use ($average) {
			return ($value->value > $average);
		});
		// output to array format
		$final_user_id_list = $users_after_filter->pluck('user_id')->toArray();

		// get the users
		$users = User::whereIn('id', $final_user_id_list)->normalUser()->inRandomOrder()->take($numberOfUsers)->get();

		// add some random users if there are not enough result
		if ($users->count() < $numberOfUsers)
		{
			$miss = $numberOfUsers - $users->count();

			$all_users = User::whereNotIn('id', $final_user_id_list)->normalUser()->inRandomOrder()->take($miss)->get();

			$users = $users->merge($all_users);
		}

		return $users;
	}

	public function alsoViewEvent(int $event_id)
	{
		$numberOfEvents = 4;

		// get the users who have view this event
		$users = Record::usersByEventID($event_id)->toArray();
		// count the users viewing record
		$events_count = Record::eventCountByUserArray($users);
		// check
		if ($events_count->count() == 0 || is_null(Event::find($event_id)))
		{
			return $this->randomJoinableEvents($numberOfEvents);
		}
		// calculate the average
		$average = $events_count->sum('count') / $events_count->count();
		// filter out the events that the number of view is lower than the average
		$events_after_filter = $events_count->filter(function ($value, $key) use ($average, $event_id) {
			return ($value['count'] > $average) && ($value['event_id'] !== $event_id);
		});
		// output to array format
		$final_event_id_list = $events_after_filter->pluck('event_id')->toArray();

		// get the events that can be join
		$events = Event::whereIn('id', $final_event_id_list)->inRandomOrder()->get();
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

	public function newestEvents()
	{
		$events = Event::orderBy('created_at', 'desc')->get();

		return Event::getJoinable($events)->take(4);
	}

	public function mostPopularEvents()
	{
		$events = Event::all()->sortByDesc(function ($event, $key) {
			return $event->numberOfJoin;
		});

		return Event::getJoinable($events)->take(4);
	}

	public function randomJoinableEvents(int $numberOfEvents)
	{
		$events = Event::inRandomOrder()->get();

		return Event::getJoinable($events)->take($numberOfEvents);
	}

	public function randomNormalUsers(int $numberOfUsers)
	{
		return User::normalUser()->take($numberOfUsers)->get();
	}
}