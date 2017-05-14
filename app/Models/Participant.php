<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Jobs\EventJoinConfirmMailSendingJob;

class Participant extends Model
{
    protected $table = 'participants';

    protected $fillable = ['user_id', 'event_id', 'grade_to_user', 'grade_to_event', 'remark_to_user', 'remark_to_event'];

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function event()
    {
    	return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function checkAndSendReminderMail()
    {
        $event = $this->event;

        if ($event && ($event->startDate->diffInDays(Carbon::now(), false) == -1))
        {
            dispatch(new EventJoinConfirmMailSendingJob($this));
        }
    }
}
