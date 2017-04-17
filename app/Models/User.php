<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Helper;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'phone', 'address_location', 'gender', 'self_introduction', 'career', 'icon_image', 'allow_email', 'interest_skills', 'available_time', 'available_area', 'year_of_volunteer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'interest_skills' => 'json',
        'available_time' => 'json',
        'available_area' => 'json'
    ];

    protected $users_groups_relation_type;

    function __construct($attributes = array())
    {
        parent::__construct($attributes);

        $this->users_groups_relation_type = Helper::getConstantArray('users_groups_relation_type')['value'];
    }

    public function getIconPathAttribute()
    {
        return '/storage/user_icon/' . $this->icon_image;
    }

    public function getIsNormalUserAttribute()
    {
        return in_array($this->type, [Helper::getKeyByArrayNameAndValue('user_type', '普通會員'), Helper::getKeyByArrayNameAndValue('user_type', '活躍會員')]);
    }

    public function markedGroup()
    {
        return $this->belongsToMany('App\Models\Group', 'users_groups_relation', 'user_id', 'group_id')->where('type', $this->users_groups_relation_type['marked'])->orderBy('created_at', 'desc');
    }

    public function markedEvent()
    {
        return $this->belongsToMany('App\Models\Event', 'users_events_relation', 'user_id', 'event_id')->orderBy('created_at', 'desc');
    }

    public function groups()
    {
        return $this->hasMany('App\Models\Group', 'user_id')->orderBy('created_at', 'desc');
    }

    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'participants', 'user_id', 'event_id')->orderBy('created_at', 'desc');
    }

    public function history_events()
    {
        return $this->belongsToMany('App\Models\Event', 'participants', 'user_id', 'event_id')->where('events.status', 2)->orderBy('created_at', 'desc');
    }

    public function history_events_with_good_grade()
    {
        return $this->belongsToMany('App\Models\Event', 'participants', 'user_id', 'event_id')
            ->where(function ($query) {
                return $query
                    ->where(function ($query) {
                        return $query->whereIn('participants.grade_to_user', [4, 5])
                                    ->whereNull('participants.grade_to_event');
                    })
                    ->orWhere(function ($query) {
                        return $query->whereNull('participants.grade_to_user')
                                    ->whereIn('participants.grade_to_event', [4, 5]);
                    })
                    ->orWhere(function ($query) {
                        return $query->whereIn('participants.grade_to_user', [4, 5])
                                    ->whereIn('participants.grade_to_event', [4, 5]);
                    });
            })
            ->orderBy('created_at', 'desc')->withPivot('grade_to_user', 'grade_to_event');
    }

    public function scopeNormalUser($query)
    {
        return $query->whereIn('type', [1, 2]);
    }
} 