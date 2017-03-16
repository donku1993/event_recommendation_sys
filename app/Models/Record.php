<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';

    protected $fillable = ['ip', 'user_id', 'group_id', 'event_id'];

    public function user() {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function group() {
    	return $this->belongsTo('App\Models\Group', 'group_id');
    }

    public function event() {
    	return $this->belongsTo('App\Models\Event', 'event_id');
    }
}
