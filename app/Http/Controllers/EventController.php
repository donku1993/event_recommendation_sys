<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Event;
use App\Models\Group;
use App\Models\Helper;
use App\Http\Controllers\StatusGetterTrait;

class EventController extends Controller
{    
    use StatusGetterTrait;

    protected function status_array(Event $event = null)
    {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_marked_event' => $this->isMarkedEvent($event),
                'is_event_manager' => $this->isEventManager($event),
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
                'content' => 'required|integer',
                'location' => 'required|integer',
                'type' => 'required',
                'previewImage' => 'image',
                'scheldule' => '',
                'requirement' => '',
                'remark' => '',
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
        $data = [
                'events' => $events
            ];

        // delete this line to pass data to view
        dd($data);

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
                                'group_id' => 'required'
                            )
                        );

        $this->validate($request, $validate_array);

        $data = $request->all();
        $group = Group::isGroup()->with(['markedUsers', 'manager', 'events'])->find($data['group_id']);

        if ($this->isGroupManager($group))
        {
            $data = Helper::JsonDataConverter($data, 'bonus_skills', 'interest_skills');
            $event = Event::create([
                    'signUpEndDate' => $data['signUpEndDate'],
                    'startDate' => $data['startDate'],
                    'endDate' => $data['endDate'],
                    'numberOfPeople' => $data['numberOfPeople'],
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'location' => $data['location'],
                    'type' => $data['type'],
                    'schedule' => $data['schedule'],
                    'requirement' => $data['requirement'],
                    'remark' => $data['remark'],
                    'bonus_skills' => $data['bonus_skills']
                ]);

            $event->previewImage = $this->imageUpload('event_preview_image', $event->id, $data['previewImage']);
            $event->save();

            if ($event)
            {
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

            return view('event.info', $data);
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
        $user = Auth::user();

        if ($user)
        {
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
        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->canJoin()->find($id);

        if ($this->isLogin() && $event && !$this->isEventManager($event))
        {
            $user = Auth::user();

            DB::table('participants')->insert([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
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
        $validate_array = $this->basicValidationArray();

        $this->validate($request, $validate_array);

        $data = $request->all();
        $user = Auth::uesr();

        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($id);

        if ($this->isEventManager($event))
        {
            $data = Helper::JsonDataConverter($data, 'bonus_skills', 'interest_skills');
            $event->fill([
                    'signUpEndDate' => $data['signUpEndDate'],
                    'startDate' => $data['startDate'],
                    'endDate' => $data['endDate'],
                    'numberOfPeople' => $data['numberOfPeople'],
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'location' => $data['location'],
                    'type' => $data['type'],
                    'schedule' => $data['schedule'],
                    'requirement' => $data['requirement'],
                    'remark' => $data['remark'],
                    'bonus_skills' => $data['bonus_skills'],
                    'previewImage' => $this->imageUpload('event_preview_image', $event->id, $data['previewImage'])
                ]);

            $result = $event->save();

            return [
                'message' => 'success',
                'event_id' => $event->id
            ];
        }

        return ['message' => 'need to be a manager of this event'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete this line to pass data to view
        dd($id);
    }
}
