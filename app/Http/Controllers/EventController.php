<?php
namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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

        $data = [
                'events' => $events,
                'keywords' => (object)$keywords
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
        if ($this->isManager())
        {
            $user = Auth::user();

            return view('event.create', ['groups' => $user->groups]);
        }
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

        if ($this->isGroupManager($group))
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
                $event->previewImage = $this->imageUpload('event_cover', $event->id, $request->input('previewImage', null));
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

            return ['message' => 'error'];
        }

        return ['message' => 'need to be a group manager'];
    }

    /**
     * Display the participants of a event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showMember($id)
    {
        $event = Event::with(['markedUsers', 'organizer', 'co_organizer', 'participants'])->find($id);

        if ($event)
        {
            $status_array = $this->status_array($event);
            $data = [
                    'event' => $event,
                    'participants' => new Paginator($event->participants, 10),
                    'status_array' => $status_array
                ];

            return view('event.member', $data);
        }
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

        if ($event)
        {
            $status_array = $this->status_array($event);
            $data = [
                    'event' => $event,
                    'status_array' => $status_array
                ];

            return view('event.edit', $data);
        }
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

        return redirect()->route('event.info', $id);
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

        if ($this->isLogin() && $event && !$this->isEventManager($event) && $event->canJoin())
        {
            $user = Auth::user();

            $participant = Participant::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
        }

        return redirect()->route('event.info', $id);
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

        if ($this->isParticipantCanEvaluate($event))
        {
            $participant = Participant::where('event_id', $event->id)->where('user_id', Auth::user()->id)->first();

            $participant->fill([
                    'grade_to_event' => $data['grade'],
                    'remark_to_event' => $data['remark'],
                ]);

            $participant->save();
        }

        return redirect()->route('event.info', $id);
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
                            ]
                        );

        $this->validate($request, $validate_array);

        $data = $request->all();

        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if ($this->isEventManager($event))
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

            $event->save();

            return [
                'message' => 'success',
                'event_id' => $event->id
            ];
        }

        return ['message' => 'need to be a manager of this event'];
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

        if ($this->isEventManager($event))
        {
            $event->fill([
                    'previewImage' => $this->imageUpload('event_cover', $event->id, $request->input('previewImage', null)),
                ]);

            $event->save();
        }

        return redirect()->route('event.info');
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

        if ($event)
        {
            $event->show = 0;
            $event->save();
        
            return ['message' => 'success'];
        }

        return ['message' => 'there is no event with id = ' . $id];
    }
}