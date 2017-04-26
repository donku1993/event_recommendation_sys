<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Group;
use App\Models\Helper;
use App\Models\Participant;
use App\Http\Controllers\RecommendationTrait;

class EventController extends Controller
{
    use RecommendationTrait;

    protected function status_array(Event $event = null)
    {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_manager' => $this->isManager(),
                'is_participant' => $this->isParticipant($event),
                'is_marked_event' => $this->isMarkedEvent($event),
                'is_event_manager' => $this->isEventManager($event),
                'is_participant_can_evaluate' => $this->isParticipantCanEvaluate($event),
                'is_manager_can_evaluate' => $this->isManagerCanEvaluate($event),
            ];
    }

    protected function basicValidationArray()
    {
        return [
                'signUpEndDate' => 'required|date|after:tomorrow',
                'startDate' => 'required|date|after:tomorrow',
                'endDate' => 'required|date|after:startDate',
                'numberOfPeople' => 'required|integer|min:1',
                'title' => 'required|max:255',
                'content' => 'required',
                'location' => 'required|integer',
                'type' => 'required'
            ];
    }

    protected function searchValidationArray()
    {
        return [
                'time_from' => 'date|nullable',
                'time_to' => 'date|nullable',
                'event_name' => 'string|nullable',
                'type' => 'integer|nullable',
                'location' => 'integer|nullable'
            ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, $this->searchValidationArray());

        $keywords = $request->all();
        $events = Event::search($keywords)->paginate(8);
        
        foreach ($this->searchValidationArray() as $key => $value) {
            $keywords[$key] = (!isset($keywords[$key]) || is_null($keywords[$key])) ? "" : $keywords[$key];
        }

        $status_array = $this->status_array();

        $data = [
            'events' => $events,
            'keywords' => (object)$keywords,
            'status_array' => $status_array
        ];

        return view('event.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->isManager() || $this->isAdmin())
        {
            $user = Auth::user();

            $status_array = $this->status_array();

            $data = [
                'groups' => $user->groups,
                'status_array' => $status_array
            ];

            return view('event.create', $data);
        }

        return back()->withInput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate_array = array_merge(
                            $this->basicValidationArray(),
                            array(
                                'group_id' => 'required',
                                'previewImage' => 'image',
                                'schedule' => '',
                                'requirement' => '',
                                'remark' => '',
                            )
                        );

        $this->validate($request, $validate_array);

        $data = $request->all();
        $group = Group::isGroup()->with(['markedUsers', 'manager', 'events'])->find($data['group_id']);

        if ($this->isGroupManager($group)
            || $this->isAdmin())
        {
            $data = Helper::JsonDataConverter($data, 'bonus_skills', 'interest_skills');
            $event = Event::create([
                    'signUpEndDate' => new Carbon($data['signUpEndDate']),
                    'startDate' => new Carbon($data['startDate']),
                    'endDate' => new Carbon($data['endDate']),
                    'numberOfPeople' => $data['numberOfPeople'],
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'location' => $data['location'],
                    'type' => $data['type'],
                    'schedule' => $request->input('schedule', ''),
                    'requirement' => $request->input('requirement', ''),
                    'remark' => $request->input('remark', ''),
                    'bonus_skills' => $data['bonus_skills']
                ]);

            if ($event)
            {
                $event->previewImage = $this->imageUpload('event_cover', $event->id, $request->file('previewImage', null));
                $event->save();

                DB::table('groups_events_relation')->insert([
                        'group_id' => $group->id,
                        'event_id' => $event->id,
                        'main' => 1
                    ]);

                return [
                    'message' => 'success',
                    'event_id' => $event->id
                ];
            }

            return response()->json(['message' => 'error'], 422);
        }

        return response()->json(['message' => 'need to be a group manager'], 422);
    }

    /**
     * Display the participants of a event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showMember($id, Request $request)
    {
        $event = Event::with(['markedUsers', 'organizer', 'co_organizer', 'participants'])->find($id);

        $page = $request->input('page', 1);

        if ($this->isEventManager($event) || $this->isParticipant($event) || $this->isAdmin())
        {
            $status_array = $this->status_array($event);
            $data = [
                    'event' => $event,
                    'participants' => $this->collectionPaginate($event->participants, 10, $page, route('event.member', $event->id)),
                    'status_array' => $status_array,
                    'page' => $page
                ];

            return view('event.member', $data);
        }

        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if ($event)
        {
            $status_array = $this->status_array($event);
            $data = [
                    'event' => $event,
                    'also_view_events' => $this->alsoViewEvent($id),
                    'status_array' => $status_array
                ];

            return view('event.info', $data);
        }

        return back()->withInput();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if (($this->isEventManager($event) || $this->isAdmin()) && $event && !$event->isFinished)
        {
            $status_array = $this->status_array($event);
            $data = [
                    'event' => $event,
                    'status_array' => $status_array
                ];

            return view('event.edit', $data);
        }

        return back()->withInput();
    }

    /**
     * User mark a event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mark($id)
    {
        if ($this->isLogin())
        {
            $user = Auth::user();

            $record = DB::table('users_events_relation')
                            ->where('user_id', $user->id)
                            ->where('event_id', $id)
                            ->first();

            if ($record)
            {
                DB::table('users_events_relation')
                        ->where('user_id', $user->id)
                        ->where('event_id', $id)
                        ->delete();
            } else {
                DB::table('users_events_relation')
                        ->insert([
                                'user_id' => $user->id,
                                'event_id' => $id
                            ]);
            }
        }

        return back()->withInput();
    }   

    /**
     * User join a event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function join($id)
    {
        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if ($this->isLogin() && $event && !$this->isEventManager($event) && $event->isJoinableEvent)
        {
            $user = Auth::user();

            $participant = Participant::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
        }

        return back()->withInput();
    }

    /**
     * Give a grade to event by participant
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function evaluation(Request $request, $id)
    {
        $validate_array = [
                            'grade' => 'integer|min:1|max:5',
                            'remark' => '',
                        ];

        $this->validate($request, $validate_array);

        $data = $request->all();

        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if ($this->isParticipantCanEvaluate($event)
            || $this->isAdmin())
        {
            $participant = Participant::where('event_id', $event->id)->where('user_id', Auth::user()->id)->first();

            if ($participant)
            {
                $participant->fill([
                        'grade_to_event' => $data['grade'],
                        'remark_to_event' => $data['remark'],
                    ]);

                $participant->save();
            }
        }

        return back()->withInput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate_array = array_merge(
                            $this->basicValidationArray(),
                            [
                                'schedule' => '',
                                'requirement' => '',
                                'remark' => '',
                                'previewImage' => 'image',
                            ]
                        );

        $this->validate($request, $validate_array);

        $data = $request->all();

        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if (($this->isEventManager($event) || $this->isAdmin()) && $event && !$event->isFinished)
        {
            $data = Helper::JsonDataConverter($data, 'bonus_skills', 'interest_skills');
            $event->fill([
                    'signUpEndDate' => new Carbon($data['signUpEndDate']),
                    'startDate' => new Carbon($data['startDate']),
                    'endDate' => new Carbon($data['endDate']),
                    'numberOfPeople' => $data['numberOfPeople'],
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'location' => $data['location'],
                    'type' => $data['type'],
                    'schedule' => $request->input('schedule', ''),
                    'requirement' => $request->input('requirement', ''),
                    'remark' => $request->input('remark', ''),
                    'bonus_skills' => $data['bonus_skills'],
                ]);

            if (isset($data['previewImage']))
            {
                $event->previewImage = $this->imageUpload('event_cover', $event->id, $request->file('previewImage', null));
            }

            $event->save();

            return [
                'message' => 'success',
                'event_id' => $event->id
            ];
        }

        return response()->json(['need to be a manager of this event' => 'error'], 422);
    }

    /**
     * Save the uploaded image and update the column resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cover_update(Request $request, $id)
    {
        $validate_array = ['previewImage' => 'image'];

        $this->validate($request, $validate_array);

        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if ($this->isEventManager($event)
            || $this->isAdmin())
        {
            $event->fill([
                    'previewImage' => $this->imageUpload('event_cover', $event->id, $request->file('previewImage', null)),
                ]);

            $event->save();
        }

        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);

        if ($this->isEventManager($event)
            || $this->isAdmin())
        {
            $event->show = 0;
            $event->save();
        
            return ['message' => 'success'];
        }

        return ['message' => 'there is no event with id = ' . $id];
    }

    public function calculate_all_similarity()
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->fireSimilarityCalculateUserGivenJob($user->id);
        }
    }
}