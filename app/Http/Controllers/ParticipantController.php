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

        if ($this->isManagerCanEvaluate($event)
            || $this->isAdmin())
        {
            $data = $this->evaluation_data_pre_processing($data);

            foreach ($data as $key => $value)
            {
                $participant = Participant::where('event_id', $event->id)->where('id', $key)->first();

                if ($participant)
                {
                    $participant->fill([
                            'grade_to_user' => $value['grade'],
                            'remark_to_user' => $value['remark'],
                        ]);

                    $participant->save();
                }
            }
        }

        return redirect()->route('event.member', $event_id);
    }

    public function evaluation_data_pre_processing($data)
    {
        $result = [];

        foreach ($data as $key => $value) {
            $pos = strpos($key, 'grade_');

            if ($pos !== false)
            {
                $id = (int)substr($key, 6);

                $result[$id]['grade'] = $value;

                continue;
            }

            $pos = strpos($key, 'remark_');

            if ($pos !== false)
            {
                $id = (int)substr($key, 7);

                $result[$id]['remark'] = $value;

                continue;
            }
        }

        foreach ($result as $key => $value) {
            $result[$key]['grade'] = (!isset($value['grade'])) ? null : $value['grade'];
            $result[$key]['remark'] = (!isset($value['remark'])) ? null : $value['remark'];
        }

        return $result;
    }
}
