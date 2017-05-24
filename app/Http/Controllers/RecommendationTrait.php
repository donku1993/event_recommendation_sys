<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Record;
use App\Models\Helper;
use App\Models\Similarity;
use App\Models\SimilarityCalculationJobRecord;
use App\Jobs\similarityCalculationForUserGivenJob;
use App\Jobs\similarityCalculationForEventGivenJob;
use App\Jobs\similarityCalculationEventToEventJob;
use App\Jobs\recommendationMailSendingJob;

use App\Models\CosineSimilarity;

trait RecommendationTrait
{
	public function calculate_similarity(int $user_id, int $event_id)
	{
		$mark = 0;

		$user = User::find($user_id);
		$event = Event::find($event_id);

		// interests and skills compare, grade: 0 to 5
		$mark += 5 * $this->interest_skills_compare($user, $event);

		// time compare, grade: 0 to 3
		$mark += 3 * $this->time_compare($user, $event);

		// address compare, grade: 0 to 2
		$mark += 2 * $this->address_compare($user, $event);

		// user's marked groups are organizer or co_organizer? grade: 0 to 3
		$mark += 3 * $this->is_created_by_marked_group($user, $event);

		// user's marked events are created by the same organizer or co_organizer?: 0 to 3
		$mark += 3 * $this->is_created_by_same_group_of_marked_event($user, $event);

		// user's marked events: compare the similarity of the events (cosine similarity): 0 to 8
		$mark += 8 * $this->is_similarity_to_marked_events($user, $event);

		// user's history events which has evaluation mark 4, 5: compare the similarity of the events (cosine similarity): 0 to 8
		$mark += 8 * $this->is_similarity_to_history_events($user, $event);

		// compare the type of the event and the year of volunteer of user: 0 to 2
		$mark += 2 * $this->user_year_of_volunteer_and_event_type($user, $event);

		return $mark;
	}

	protected function interest_skills_compare(User $user, Event $event)
	{
		$user_interest_skills = $user->interest_skills;
		$event_bonus_skills = $event->bonus_skills;

		$all = 0;
		$match = 0;

		foreach ($event_bonus_skills as $key => $value)
		{
			$all += ($value) ? 1 : 0;
			$match += ($value && $value == $user_interest_skills[$key]) ? 1 : 0;
		}

		if ($all == 0)
		{
			return 0;
		}

		return $match / $all;
	}

	protected function time_compare(User $user, Event $event)
	{
		$user_avaliable_time = $user->available_time;
		$event_time_start = $event->startDate;
		$event_time_end = $event->endDate;

		if ($event_time_start->day !== $event_time_end->day)
		{
			return 0;
		}
		else
		{
			$nine_oclock = Carbon::create($event_time_start->year, $event_time_start->month, $event_time_start->day, 9, 0, 0);
			$twelve_oclock = Carbon::create($event_time_start->year, $event_time_start->month, $event_time_start->day, 12, 0, 0);
			$eighteen_oclock = Carbon::create($event_time_start->year, $event_time_start->month, $event_time_start->day, 18, 0, 0);
			$twenty_two_oclock = Carbon::create($event_time_start->year, $event_time_start->month, $event_time_start->day, 22, 0, 0);

			$all = $event_time_start->diffInMinutes($event_time_end, false);
			$match = 0;
			$end = false;

        	// 09:00-12:00
			if ($user_avaliable_time[0] && $event_time_start->hour < 12)
			{
				if ($event_time_end->hour < 12 && $event_time_end > 9)
				{
					$match = $event_time_start->diffInMinutes($event_time_end, false);
					$end = true;
				} else {
					$match = $event_time_start->diffInMinutes($twelve_oclock, false);
				}
			}
			$event_time_start = $twelve_oclock;

        	// 12:00-18:00
			if (!$end && $user_avaliable_time[1] && $event_time_start->hour < 18)
			{
				if ($event_time_end->hour < 18 && $event_time_end > 12)
				{
					$match = $event_time_start->diffInMinutes($event_time_end, false);
					$end = true;
				} else {
					$match = $event_time_start->diffInMinutes($eighteen_oclock, false);
				}
			}
			$event_time_start = $eighteen_oclock;

        	// 18:00-22:00
        	if (!$end && $user_avaliable_time[2] && $event_time_start->hour < 22)
        	{
				if ($event_time_end->hour < 22 && $event_time_end > 18)
				{
					$match = $event_time_start->diffInMinutes($event_time_end, false);
					$end = true;
				} else {
					$match = $event_time_start->diffInMinutes($twenty_two_oclock, false);
				}
        	}

			if ($all == 0)
			{
				return 0;
			}

			return $match / $all;
		}
	}

	protected function address_compare(User $user, Event $event)
	{
		$user_avaliable_area = $user->available_area;
		$event_location = $event->location;

		return ($user_avaliable_area[$event_location]) ? 1 : 0;
	}

	protected function is_created_by_marked_group(User $user, Event $event)
	{
		$user_marked_groups = $user->markedGroup->pluck('id');
		$event_organizers = $event->all_organizer->pluck('id');

		$all = $event_organizers->count();
		$match = $event_organizers->intersect($user_marked_groups)->count();

		if ($all == 0)
		{
			return 0;
		}

		return $match / $all;
	}

	protected function is_created_by_same_group_of_marked_event(User $user, Event $event)
	{
		$user_marked_event = $user->markedEvent;
		$group_list = collect([]);
		$event_organizers = $event->all_organizer->pluck('id');

		foreach ($user_marked_event as $event)
		{
			$group_list = $group_list->merge($event->all_organizer->pluck('id'));
		}

		$group_list = $group_list->unique();

		$all = $event_organizers->count();
		$match = $event_organizers->intersect($group_list)->count();

		if ($all == 0)
		{
			return 0;
		}

		return $match / $all;
	}

	protected function is_similarity_to_marked_events(User $user, Event $event)
	{
		$user_marked_events = $user->markedEvent;
		$sim_grade_list = collect([]);

		foreach ($user_marked_events as $user_marked_event)
		{
			if ($event->id != $user_marked_event->id)
			{
				$sim_grade_list->push($this->get_cosine_similarity($event, $user_marked_event));
			}
		}

		$total = $sim_grade_list->count();

		$sim_grade_list = $sim_grade_list->filter(function ($value, $key){
			return $value > 0.5;
		});

		if ($sim_grade_list->count() == 0 || $total == 0)
		{
			return 0;
		}

		$average = $sim_grade_list->sum() / $sim_grade_list->count();

		return $average * ( $sim_grade_list->count() / $total );
	}

	protected function is_similarity_to_history_events(User $user, Event $event)
	{
		$user_history_events = $user->history_events_with_good_grade;
		$sim_grade_list = collect([]);

		foreach ($user_history_events as $user_history_event)
		{
			if ($event->id != $user_history_event->id)
			{
				$sim_grade_list->push($this->get_cosine_similarity($event, $user_history_event));
			}
		}

		$total = $sim_grade_list->count();

		$sim_grade_list = $sim_grade_list->filter(function ($value, $key){
			return $value > 0.5;
		});

		if ($sim_grade_list->count() == 0 || $total == 0)
		{
			return 0;
		}

		$average = $sim_grade_list->sum() / $sim_grade_list->count();

		return $average * ( $sim_grade_list->count() / $total );
	}

	protected function user_year_of_volunteer_and_event_type(User $user, Event $event)
	{
		if ($user->year_of_volunteer == Helper::getKeyByArrayNameAndValue('year_of_volunteer', '沒有經驗')
			&& $event->type == Helper::getKeyByArrayNameAndValue('event_type', '志工培訓'))
		{
			return 1;
		}

		return 0;
	}

	protected function get_cosine_similarity(Event $event_1, Event $event_2)
	{
		$record = DB::table('similarity_event_to_event')
					->where(function ($query) use ($event_1, $event_2)
						{
							return $query->where('event_one_id', $event_1->id)->where('event_two_id', $event_2->id);
						})
					->orWhere(function ($query) use ($event_1, $event_2)
						{
							return $query->where('event_two_id', $event_1->id)->where('event_one_id', $event_2->id);
						})
					->first();

		if ($record)
		{
			return $record->value;
		}

		$value = CosineSimilarity::similarity($event_1->toFeatures(), $event_2->toFeatures());

		DB::table('similarity_event_to_event')->insert([
				'event_one_id' => $event_1->id,
				'event_two_id' => $event_2->id,
				'value' => $value,
				'created_at' => Carbon::now()
			]);

		return $value;
	}

	public static function fireSimilarityCalculateUserGivenJob(int $user_id)
	{
		if (SimilarityCalculationJobRecord::countWaitingOrRunningJobWithSameUserID($user_id) == 0)
		{
			dispatch(new similarityCalculationForUserGivenJob($user_id));
		}
	}

	public static function fireSimilarityCalculateEventGivenJob(int $event_id)
	{
		if (SimilarityCalculationJobRecord::countWaitingOrRunningJobWithSameEventID($event_id) == 0)
		{
			dispatch(new similarityCalculationForEventGivenJob($event_id));
		}
	}

	public static function fireSimilarityCalculateEventToEventJob(int $event_id)
	{
		if (SimilarityCalculationJobRecord::countWaitingOrRunningJobForEventToEvent($event_id) == 0)
		{
			dispatch(new similarityCalculationEventToEventJob($event_id));
		}
	}

	public static function fireRecommendationMailSendingJob(int $user_id, int $event_id)
	{
		$user = User::find($user_id);
		$event = Event::find($event_id);

		if ($user && $user->allow_email == 1 && $event)
		{
			dispatch(new recommendationMailSendingJob($user, $event));
		}
	}

	public function update_similarity(int $user_id, int $event_id, float $value)
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

	public function store_similarity(int $user_id, int $event_id, float $value)
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

	public function similarityCalculation_event_to_event(int $event_id)
	{
		$event_1 = Event::find($event_id);

		if ($event_1)
		{
			$other_events = Event::where('id', '<>', $event_1->id)->get();

			foreach ($other_events as $event_2)
			{
				$value = CosineSimilarity::similarity($event_1->toFeatures(), $event_2->toFeatures());

				$record = DB::table('similarity_event_to_event')
						->where(function ($query) use ($event_1, $event_2)
							{
								return $query->where('event_one_id', $event_1->id)->where('event_two_id', $event_2->id);
							})
						->orWhere(function ($query) use ($event_1, $event_2)
							{
								return $query->where('event_two_id', $event_1->id)->where('event_one_id', $event_2->id);
							})
						->first();

				if ($record)
				{
					if ($record->value !== $value)
					{
						DB::table('similarity_event_to_event')
							->where('id', $record->id)
							->update([
									'value' => $value,
									'updated_at' => Carbon::now()
								]);
					}
				}
				else
				{
					DB::table('similarity_event_to_event')->insert([
						'event_one_id' => $event_1->id,
						'event_two_id' => $event_2->id,
						'value' => $value,
						'created_at' => Carbon::now()
					]);
				}
			}
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

	public function newestEvents(int $num = null)
	{
		$events = Event::orderBy('created_at', 'desc')->get();

		if (!is_null($num))
		{
			return Event::getJoinable($events)->take($num);
		}

		return Event::getJoinable($events);
	}

	public function mostPopularEvents(int $num = null)
	{
		$events = Event::all()->sortByDesc(function ($event, $key) {
			return $event->popularLevel;
		});

		if (!is_null($num))
		{
			return Event::getJoinable($events)->take($num);
		}

		return Event::getJoinable($events);
	}

	public function sort_by_recommendation_user_given(int $user_id)
	{
		// get all the similarity value of the user to all events
		$similarities = Similarity::where('user_id', $user_id)->orderBy('value', 'desc')->get();
		// filter out the similarity which for the ended event
		$similarities = $similarities->filter(function ($value, $key) {
			return $value->isForJoinableEvent;
		});

		// output to array format
		$final_event_id_list = $similarities->pluck('event_id')->toArray();

		// get the events that can be join
		$events = Event::whereIn('id', $final_event_id_list)->get();

		// sorted it by similarity value on desc
		$events = $events->sortBy(function($event) use ($final_event_id_list) {
			return array_search($event->id, $final_event_id_list);
		});

		return $events;
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