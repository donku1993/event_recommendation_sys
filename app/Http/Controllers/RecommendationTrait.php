<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Record;
use App\Models\Similarity;
use App\Models\SimilarityCalculationJobRecord;
use App\Jobs\similarityCalculationForUserGivenJob;
use App\Jobs\similarityCalculationForEventGivenJob;

trait RecommendationTrait
{
	public function fireSimilarityCalculateUserGivenJob(int $user_id)
	{
		if (SimilarityCalculationJobRecord::countWaitingOrRunningJobWithSameUserID($user_id) == 0)
		{
			dispatch(new similarityCalculationForUserGivenJob($user_id));
		}
	}

	public function fireSimilarityCalculateEventGivenJob(int $event_id)
	{
		if (SimilarityCalculationJobRecord::countWaitingOrRunningJobWithSameEventID($event_id) == 0)
		{
			dispatch(new similarityCalculationForEventGivenJob($event_id));
		}
	}

	public function calculate_similarity(int $user_id, int $event_id)
	{
		return 50.0;
	}

	public function update_similarity(int $user_id, int $event_id, double $value)
	{
		$s = Similarity::where('user_id', $user_id)->where('event_id', $event_id)->first();

		if ($s)
		{
			$s->fill(['value' => $value]);
			$s->save();
		} else
		{
			$this->store_similarity($user_id, $event_id, $value);
		}
	}

	public function store_similarity(int $user_id, int $event_id, double $value)
	{
		if (User::find($user_id) && Event::find($event_id))
		{
			$s = Similarity::create([
					'user_id' => $user_id,
					'event_id' => $event_id,
					'value' => $value
				]);
		}
	}

	public function similarityCalculation_user_given(int $user_id)
	{
		if (User::find($user_id))
		{
			$exist_similarities = Similarity::where('user_id', $user_id)->get();
			// filter out the similarity which for the ended event
			$exist_similarities = $exist_similarities->filter(function ($value, $key) {
				return $value->isForJoinableEvent;
			})->pluck('event_id')->toArray();

			// re-calculate the exist similarity grade
			foreach ($exist_similarities as $event_id) {
				if (Event::find($event_id))
				{
					$mark = $this->calculate_similarity($user_id, $event_id);
					$this->update_similarity($user_id, $event_id, $mark);
				}
			}

			$new_similarities = Event::whereNotIn('id', $exist_similarities)->get();
			$new_similarities = Event::getJoinable($new_similarities)->pluck('id')->toArray();
			// calculate the similarity grade for new events
			foreach ($new_similarities as $event_id) {
				if (Event::find($event_id))
				{
					$mark = $this->calculate_similarity($user_id, $event_id);
					$this->store_similarity($user_id, $event_id, $mark);
				}
			}
		}
	}

	public function similarityCalculation_event_given(int $event_id)
	{
		$event = Event::find($event_id);

		if ($event && $event->isJoinableEvent)
		{
			$exist_similarities = Similarity::where('event_id', $event_id)->get();
			// filter out the similarity which for the ended event
			$exist_similarities = $exist_similarities->filter(function ($value, $key) {
				return $value->isForNormalUser;
			})->pluck('user_id')->toArray();

			// re-calculate the exist similarity grade
			foreach ($exist_similarities as $user_id) {
				if (User::find($user_id))
				{
					$mark = $this->calculate_similarity($user_id, $event_id);
					$this->update_similarity($user_id, $event_id, $mark);
				}
			}

			$new_similarities = User::whereNotIn('id', $exist_similarities)->normalUser()->get()->pluck('id')->toArray();
			// calculate the similarity grade for new events
			foreach ($new_similarities as $user_id) {
				if (User::find($user_id))
				{
					$mark = $this->calculate_similarity($user_id, $event_id);
					$this->store_similarity($user_id, $event_id, $mark);
				}
			}
		}
	}

	public function recommendation_user_given(int $user_id)
	{
		$numberOfEvents = 4;

		// get all the similarity value of the user to all events
		$similarities = Similarity::where('user_id', $user_id)->get();
		// filter out the similarity which for the ended event
		$similarities = $similarities->filter(function ($value, $key) {
			return $value->isForJoinableEvent;
		});

		// check is there enough recoud
		if ($similarities->count() == 0 || is_null(User::find($user_id)))
		{
			return $this->randomJoinableEvents($numberOfEvents);
		}
		// calculate the average
		$average = $similarities->sum('value') / $similarities->count();
		// filter out the events that the value is lower than the average
		$events_after_filter = $similarities->filter(function ($value, $key) use ($average) {
			return ($value->value >= $average);
		});
		// output to array format
		$final_event_id_list = $events_after_filter->pluck('event_id')->toArray();

		// get the events that can be join
		$events = Event::whereIn('id', $final_event_id_list)->inRandomOrder()->take($numberOfEvents)->get();

		// add some random events if there are not enough result
		if ($events->count() < $numberOfEvents)
		{
			$miss = $numberOfEvents - $events->count();

			$events = $events->merge($this->randomJoinableEventsWithoutIDs($miss, $final_event_id_list));
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
		// number of recommend user is 1.2 times of the upper limit of the event
		$numberOfUsers = floor($event->numberOfPeople * 1.2);
		// get all the similarity value of the event to all users
		$similarities = Similarity::where('event_id', $event_id)->get();

		// check is there enough recoud
		if ($similarities->count() == 0)
		{
			return $this->randomNormalUsers($numberOfUsers);
		}
		// calculate the average
		$average = $similarities->sum('value') / $similarities->count();
		// filter out the users that the value is lower than the average
		$users_after_filter = $similarities->filter(function ($value, $key) use ($average) {
			return ($value->value >= $average);
		});
		// output to array format
		$final_user_id_list = $users_after_filter->pluck('user_id')->toArray();

		// get the users
		$users = User::whereIn('id', $final_user_id_list)->normalUser()->inRandomOrder()->take($numberOfUsers)->get();

		// add some random users if there are not enough result
		if ($users->count() < $numberOfUsers)
		{
			$miss = $numberOfUsers - $users->count();

			$users = $users->merge($this->randomNormalUsersWithoutIDs($miss, $final_user_id_list));
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
		// filter out the similarity which for the ended event
		$events_count = $events_count->filter(function ($value, $key) {
			return $value->isForJoinableEvent;
		});
		// check is there enough recoud
		if ($events_count->count() == 0 || is_null(Event::find($event_id)))
		{
			return $this->randomJoinableEvents($numberOfEvents);
		}
		// calculate the average
		$average = $events_count->sum('count') / $events_count->count();
		// filter out the events that the number of view is lower than the average
		$events_after_filter = $events_count->filter(function ($value, $key) use ($average, $event_id) {
			return ($value['count'] >= $average) && ($value['event_id'] !== $event_id);
		});
		// output to array format
		$final_event_id_list = $events_after_filter->pluck('event_id')->toArray();

		// get the events that can be join
		$events = Event::whereIn('id', $final_event_id_list)->inRandomOrder()->take($numberOfEvents)->get();

		// add some random events if there are not enough result
		if ($events->count() < $numberOfEvents)
		{
			$miss = $numberOfEvents - $events->count();

			$events = $events->merge($this->randomJoinableEventsWithoutIDs($miss, $final_event_id_list));
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

		if ($numberOfEvents > 0)
		{
			return Event::getJoinable($events)->take($numberOfEvents);
		}

		return Event::getJoinable($events);
	}

	public function randomNormalUsers(int $numberOfUsers)
	{
		if ($numberOfUsers > 0)
		{
			return User::normalUser()->take($numberOfUsers)->get();
		}

		return User::normalUser()->get();
	}

	public function randomJoinableEventsWithoutIDs(int $numberOfEvents, Array $event_ids)
	{
		$events = Event::whereNotIn('id', $event_ids)->inRandomOrder()->get();
		$events = Event::getJoinable($events);

		if ($numberOfEvents > 0)
		{
			return $events->take($numberOfEvents);
		}

		return $events;
	}

	public function randomNormalUsersWithoutIDs(int $numberOfUsers, Array $user_ids)
	{
		$users = User::whereNotIn('id', $user_ids)->normalUser()->inRandomOrder()->get();

		if ($numberOfUsers > 0)
		{
			return $users->take($numberOfUsers);
		}

		return $users;
	}
}