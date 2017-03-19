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

    public function getNumberOfMarkedAttribute() {
        return $this->markedUsers->count();
    }

    public function markedUsers() {
    	return $this->belongsToMany('App\Models\User', 'users_events_relation', 'event_id', 'user_id');
    }

    public function organizer() {
    	return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->where('main', 1);
    }

    public function co_organizer() {
    	return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->where('main', 0);
    }

    public function participants() {
    	return $this->belongsToMany('App\Models\User', 'participants', 'event_id', 'user_id');
    }

    public function scopeSearch($query, Array $keywords) {
        if (isset($keywords['time_from'])) {
            $query->where('startDate', '>', $keywords['time_from']);
        }

        if (isset($keywords['time_to'])) {
            $query->where('endDate', '<', $keywords['time_to']);
        }

        if (isset($keywords['event_name'])) {
            $query->where('title', 'like', '%'.$keywords['event_name'].'%');
        }

        if (isset($keywords['type'])) {
            $query->where('type', $keywords['type']);
        }

        if (isset($keywords['location'])) {
            $query->where('location', $keywords['location']);
        }

        return $query;
    }
}
