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
        'name', 'email', 'password', 'status', 'phone', 'address_location', 'gender', 'self_introduction', 'career', 'icon_image', 'allow_email', 'interest_skills', 'available_time', 'available_area'
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

    function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->users_groups_relation_type = Helper::getConstantArray('users_groups_relation_type')['value'];
    }

    public function markedGroup() {
        return $this->belongsToMany('App\Models\Group', 'users_groups_relation', 'user_id', 'group_id')->where('type', $this->users_groups_relation_type['marked']);
    }

    public function markedEvent() {
        return $this->belongsToMany('App\Models\Event', 'users_events_relation', 'user_id', 'event_id');
    }

    public function groups() {
        return $this->hasMany('App\Models\Group', 'user_id');
    }

    public function events() {
        return $this->hasMany('App\Models\Event', 'participants', 'user_id', 'event_id');
    }
} 