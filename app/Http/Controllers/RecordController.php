<?php

namespace App\Http\Controllers;

use Auth;
use Request as GlobalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\Group;
use App\Models\Event;

class RecordController extends Controller
{
    public function groupRecord($id)
    {
    	$group = Group::isGroup()->find($id);

    	if ($group) {
    		Record::insert([
    				'ip' => GlobalRequest::ip(),
    				'user_id' => (Auth::user()) ? Auth::user()->id : null,
    				'group_id' => $group->id,
                    'created_at' => Carbon::now()
    			]);
    	}
    }

    public function eventRecord($id)
    {
    	$event = Event::find($id);

    	if ($event) {
    		Record::insert([
    				'ip' => GlobalRequest::ip(),
    				'user_id' => (Auth::user()) ? Auth::user()->id : null,
    				'event_id' => $event->id,
                    'created_at' => Carbon::now()
    			]);
    	}
    }
}
