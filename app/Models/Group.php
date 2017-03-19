<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Helper;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = ['user_id', 'name', 'registered_id', 'registered_file', 'icon_image', 'establishment_date', 'principal_name', 'email', 'phone', 'address', 'introduction', 'activity_area', 'status', 'remark'];

    protected $casts = [
    	'activity_area' => 'json'
    ];

    protected $form_status = [0, 1, 3];
    protected $users_groups_relation_type;

    function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->users_groups_relation_type = Helper::getConstantArray('users_groups_relation_type')['value'];
    }

    public function applicant() {
    	return $this->belongsTo('App\Models\User', 'user_id')->isGroupForm();
    }

    public function manager() {
    	return $this->belongsTo('App\Models\User', 'user_id')->isGroup();
    }

    public function markedUsers() {
    	return $this->belongsToMany('App\Models\User', 'users_groups_relation', 'group_id', 'user_id')->where('type', $this->users_groups_relation_type['marked']);
    }

    public function events() {
    	return $this->hasMany('App\Models\Event', 'groups_events_relation', 'group_id', 'event_id');
    }

    public function events_with_organizer() {
    	return $this->hasMany('App\Models\Event', 'groups_events_relation', 'group_id', 'event_id')->where('main', 1);
    }

    public function events_with_co_organizer() {
    	return $this->hasMany('App\Models\Event', 'groups_events_relation', 'group_id', 'event_id')->where('main', 0);
    }

    public function scopeIsGroupForm($query) {
    	return $query->whereIn('status', $this->form_status);
    }

    public function scopeIsGroup($query) {
    	return $query->whereNotIn('status', $this->form_status);
    }
}
