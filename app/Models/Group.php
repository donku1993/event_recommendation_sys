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

    protected $dates = ['establishment_date'];

    protected $form_status = [0, 1, 3];
    protected $unprocess_form_status = [0, 1];
    protected $users_groups_relation_type;

    function __construct($attributes = array()) {
        parent::__construct($attributes);

        $this->users_groups_relation_type = Helper::getConstantArray('users_groups_relation_type')['value'];
    }

    public function getIconPathAttribute() {
        return '/storage/group_icon/' . $this->icon_image;
    }

    public function applicant() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function manager() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function markedUsers() {
    	return $this->belongsToMany('App\Models\User', 'users_groups_relation', 'group_id', 'user_id')->wherePivot('type', $this->users_groups_relation_type['marked'])->orderBy('created_at', 'desc');
    }

    public function events() {
    	return $this->belongsToMany('App\Models\Event', 'groups_events_relation', 'group_id', 'event_id')->orderBy('created_at', 'desc');
    }
/*
    public function events_with_organizer() {
    	return $this->belongsToMany('App\Models\Event', 'groups_events_relation', 'group_id', 'event_id')->wherePivot('main', 1);
    }

    public function events_with_co_organizer() {
    	return $this->belongsToMany('App\Models\Event', 'groups_events_relation', 'group_id', 'event_id')->wherePivot('main', 0);
    }
*/
    public function scopeIsGroupForm($query) {
    	return $query->whereIn('status', $this->form_status);
    }

    public function scopeUnprocessForm($query) {
        return $query->whereIn('status', $this->unprocess_form_status);
    }

    public function scopeIsGroup($query) {
    	return $query->whereNotIn('status', $this->form_status);
    }

    public function scopeSearch($query, Array $keywords) {
        if (isset($keywords['group_name'])) {
            $query->where('name', 'like', '%'.$keywords['group_name'].'%');
        }

        if (isset($keywords['activity_area'])) {
            $query->where('activity_area->'.$keywords['activity_area'], true);
        }

        return $query;
    }

    public function scopeSearchForm($query, Array $keywords) {
        if (isset($keywords['group_name'])) {
            $query->where('name', 'like', '%'.$keywords['group_name'].'%');
        }

        if (isset($keywords['status'])) {
            $query->where('status', $keywords['status']);
        }

        return $query;     
    }
}
