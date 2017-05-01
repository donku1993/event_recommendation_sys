<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Http\Controllers\RecommendationTrait;

class Similarity extends Model
{
    use RecommendationTrait;

    protected $table = 'similarity';

    protected $fillable = ['user_id', 'event_id', 'value'];

    public function getIsForJoinableEventAttribute()
    {
        return $this->event->isJoinableEvent;
    }

    public function getIsForNormalUserAttribute()
    {
        return $this->user->isNormalUser;
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function event()
    {
    	return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function scopeOrderByValue($query, int $user_id)
    {
        return $query->where('user_id', $user_id)->orderBy('value', 'desc')->get();
    }

    public static function updateAll()
    {
        $users = User::all();

        foreach ($users as $user) {
            self::fireSimilarityCalculateUserGivenJob($user->id);
        }
    }

    public static function sendRecommendationMailToUser()
    {
        $users = User::allowSendMail()->normalUser()->get();

        foreach ($users as $user) {
            $most_recommend_records = self::orderByValue($user->id);

            $most_recommend_records = $most_recommend_records->filter(function ($value, $key) {
                return $value->isForJoinableEvent;
            });

            $most_recommend_record = $most_recommend_records->first();

            if ($most_recommend_record)
            {
                self::fireRecommendationMailSendingJob($user->id, $most_recommend_record->event_id);
            }
        }
    }
}
