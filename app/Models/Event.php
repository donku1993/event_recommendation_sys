<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use DB;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['signUpEndDate', 'startDate', 'endDate', 'numberOfPeople', 'previewImage', 'title', 'content', 'location', 'type', 'schedule', 'requirement', 'remark', 'status', 'bonus_skills'];
    protected $casts = [
        'bonus_skills' => 'json'
    ];

    protected $dates = ['signUpEndDate', 'startDate', 'endDate'];

    public function getNumberOfMarkedAttribute()
    {
        return $this->markedUsers->count();
    }

    public function getIconPathAttribute()
    {
        return '/storage/event_cover/' . $this->previewImage;
    }

    public function getHoursAttribute()
    {
        return $this->startDate->diffInHours($this->endDate, false);
    }

    public function getNumberOfJoinAttribute()
    {
        return DB::table('participants')->where('event_id', $this->id)->count();
    }

    public function getIsJoinableEventAttribute()
    {
        $count = DB::table('participants')->where('event_id', $this->id)->count();

        if ($this->signUpEndDate > Carbon::now() &&
            $this->numberOfPeople > $count)
        {
            return true;
        }

        return false;
    }

    public function markedUsers()
    {
    	return $this->belongsToMany('App\Models\User', 'users_events_relation', 'event_id', 'user_id')->orderBy('created_at', 'desc');
    }

    public function all_organizer()
    {
        return $this->belongsToMany('App\Models\Group', 'groups_events_relation', 'event_id', 'group_id')->orderBy('created_at', 'desc');
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
    	return $this->belongsToMany('App\Models\User', 'participants', 'event_id', 'user_id')->withPivot('status', 'grade_to_user', 'grade_to_event', 'remark_to_user', 'remark_to_event')->orderBy('created_at', 'desc');
    }

    public function toFeatures()
    {
        $features = $this->bonus_skills;
        array_push($features, $this->location);
        array_push($features, $this->type);
        array_push($features, $this->organizer[0]->id);

        return $features;
    }

    public function scopeSearch($query, Array $keywords)
    {
        if (isset($keywords['time_from']) && $keywords['time_from'] !== "")
        {
            $query->where('startDate', '>', $keywords['time_from']);
        }

        if (isset($keywords['time_to']) && $keywords['time_to'] !== "")
        {
            $query->where('endDate', '<', $keywords['time_to']);
        }

        if (isset($keywords['event_name']) && $keywords['event_name'] !== "")
        {
            $query->where('title', 'like', '%'.$keywords['event_name'].'%');
        }

        if (isset($keywords['type']) && $keywords['type'] !== "")
        {
            $query->where('type', $keywords['type']);
        }

        if (isset($keywords['location']) && $keywords['location'] !== "")
        {
            $query->where('location', $keywords['location']);
        }
        return $query;
    }

    public static function getJoinable(Collection $events)
    {
        return $events->filter(function ($event) {
            return $event->isJoinableEvent;
        });
    }
}