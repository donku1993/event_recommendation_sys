<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Event;
use App\Http\Controllers\StatusGetterTrait;

class EventController extends Controller
{    
    use StatusGetterTrait;

    public function status_array(Event $event = null) {
        return [
                'is_login' => $this->isLogin(),
                'is_admin' => $this->isAdmin(),
                'is_marked_event' => $this->isMarkedEvent($event),
                'is_event_manager' => $this->isEventManager($event),
            ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
                'time_from' => 'date|nullable',
                'time_to' => 'date|nullable',
                'event_name' => 'string|nullable',
                'type' => 'integer|nullable',
                'location' => 'integer|nullable'
            ]);

        $keywords = $request->all();
        $events = Event::search($keywords)->paginate(8);
        $data = [
                'events' => $events
            ];

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
        return view('event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
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

        if ($event) {
            $status_array = $this->status_array($event);

            $data = [
                    'event' => $event,
                    'participants' => new Paginator($event->participants, 10),
                    'status_array' => $status_array
                ];

            dd($data);

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
        
        if ($event) {
            $status_array = $this->status_array($event);

            $data = [
                    'event' => $event,
                    'status_array' => $status_array
                ];

            dd($data);

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

        if ($event) {
            $status_array = $this->status_array($event);

            $data = [
                    'event' => $event,
                    'status_array' => $status_array
                ];

            dd($data);

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
        dd($id);
    }   

    /**
     * User join a event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function join($id)
    {
        dd($id);
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
        dd($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }
}
