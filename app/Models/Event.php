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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('show', function (Builder $builder) {
            $builder->where('show', 1);
        });
    }

    protected $dates = ['signUpEndDate', 'startDate', 'endDate'];

    public function getNumberOfMarkedAttribute()
    {
        return $this->markedUsers->count();
    }

    public function getIconPathAttribute()
    {
        return '/storage/event_cover/' . $this->previewImage;
    }

    public function markedUsers()
    {
    	return $this->belongsToMany('App\Models\User', 'users_events_relation', 'event_id', 'user_id')->orderBy('created_at', 'desc');
    }

    public function organizer()
    {
    	return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->where('main', 1)->orderBy('created_at', 'desc');
    }

    public function co_organizer()
    {
    	return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->where('main', 0)->orderBy('created_at', 'desc');
    }

    public function participants()
    {
    	return $this->belongsToMany('App\Models\User', 'participants', 'event_id', 'user_id')->orderBy('created_at', 'desc');
    }

    public function scopeCanJoin($query)
    {
        return $query;
    }

    public function scopeSearch($query, Array $keywords)
    {
        if (isset($keywords['time_from']))
        {
            $query->where('startDate', '>', $keywords['time_from']);
        }

        if (isset($keywords['time_to']))
        {
            $query->where('endDate', '<', $keywords['time_to']);
        }

        if (isset($keywords['event_name']))
        {
            $query->where('title', 'like', '%'.$keywords['event_name'].'%');
        }

        if (isset($keywords['type']))
        {
            $query->where('type', $keywords['type']);
        }

        if (isset($keywords['location']))
        {
            $query->where('location', $keywords['location']);
        }

        return $query;
    }
}
