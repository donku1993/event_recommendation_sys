<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Helper;
use App\Models\Participant;

class ParticipantController extends Controller
{    
	/**
     * Give a grade to member by manager
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function evaluation(Request $request, $event_id)
    {
        $data = $request->all();

        $event = Event::with(['markedUsers', 'organizer', 'co_organizer'])->find($event_id);

        if ($this->isManagerCanEvaluate($event))
        {
            $participant = Participant::where('event_id', $event->id)->where('user_id', $data['member_id'])->first();

            $participant->fill([
                    'grade_to_user' => $data['grade'],
                    'remark_to_user' => $data['remark'],
                ]);

            $participant->save();
        }

        return redirect()->route('event.member', $event_id);
    }
}
