<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Record extends Model
{
    protected $table = 'records';

    protected $fillable = ['ip', 'user_id', 'group_id', 'event_id'];

    public function getIsForJoinableEventAttribute()
    {
        if (!is_null($this->event))
        {
            return $this->event->isJoinableEvent;
        }

        return false;
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function group()
    {
    	return $this->belongsTo('App\Models\Group', 'group_id');
    }

    public function event()
    {
    	return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function scopeByUserID($query, int $id)
    {
        return $query->where('user_id', $id);
    }

    public function scopeByUserIDArray($query, Array $ids)
    {
        return $query->whereIn('user_id', $ids);
    }

    public function scopeByEventID($query, int $id)
    {
        return $query->where('event_id', $id);
    }

    public function scopeByGroupID($query, int $id)
    {
        return $query->where('group_id', $id);
    }

    public function scopeUsersByEventID($query, int $id)
    {
        return $query->byEventID($id)->distinct()->pluck('user_id');
    }

    public function scopeEventCountByUserArray($query, Array $ids)
    {
        return $query->byUserIDArray($ids)->select('event_id', DB::raw('COUNT(event_id) as count'))->groupBy('event_id')->orderBy('count', 'desc')->get();
    }
}
