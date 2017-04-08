<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Similarity extends Model
{
    protected $table = 'similarity';

    protected $fillable = ['user_id', 'event_id', 'value'];

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function event()
    {
    	return $this->belongsTo('App\Models\Event', 'event_id');
    }
}
