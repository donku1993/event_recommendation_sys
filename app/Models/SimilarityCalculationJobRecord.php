<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimilarityCalculationJobRecord extends Model
{
    protected $table = 'similarity_calculation_job_record';

    protected $fillable = ['pass_id', 'type', 'status'];

    const USER_GIVEN_TYPE = 0;
    const EVENT_GIVEN_TYPE = 1;

    const WAITING = 0;
    const RUNNING = 1;
    const FINISHED = 2;

    public static $runningOrWaiting = [self::WAITING, self::RUNNING];

    // type:
    // 0 -> user given
    // 1 -> event given

    // status:
    // 0 -> waiting
    // 1 -> running
    // 2 -> finished

    public function scopeCountWaitingOrRunningJobWithSameUserID($query, int $user_id)
    {
    	return $query->where('pass_id', $user_id)->where('type', self::USER_GIVEN_TYPE)->whereIn('status', self::$runningOrWaiting)->get()->count();
    }

    public function scopeCountWaitingOrRunningJobWithSameEventID($query, int $event_id)
    {
    	return $query->where('pass_id', $event_id)->where('type', self::EVENT_GIVEN_TYPE)->whereIn('status', self::$runningOrWaiting)->get()->count();
    }

    public function toRunning()
    {
        $this->fill(['status' => SimilarityCalculationJobRecord::RUNNING]);
        $this->save();
    }

    public function toFinished()
    {
        $this->fill(['status' => SimilarityCalculationJobRecord::FINISHED]);
        $this->save();
    }
}
