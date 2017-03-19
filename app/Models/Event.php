<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = ['signUpEndDate', 'startDate', 'endDate', 'numberOfPeople', 'previewImage', 'title', 'content', 'location', 'type', 'schedule', 'requirement', 'remark', 'status', 'bonus_skills'];

    protected $casts = [
    	'bonus_skills' => 'json'
    ];

    public function markedUsers() {
    	return $this->belongsToMany('App\Models\User', 'users_events_relation', 'event_id', 'user_id');
    }

    public function organizer() {
    	return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->where('main', 1)->first();
    }

    public function co_organizer() {
    	return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->where('main', 0);
    }

    public function participants() {
    	return $this->hasMany('App\Models\User', 'participants', 'event_id');
    }
}
