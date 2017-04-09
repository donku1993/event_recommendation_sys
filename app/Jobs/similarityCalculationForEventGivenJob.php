<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Http\Controllers\RecommendationTrait;
use App\Models\SimilarityCalculationJobRecord;

class similarityCalculationForEventGivenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RecommendationTrait;

    protected $event_id;
    protected $similarity_calculation_job_record;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $event_id)
    {
        $this->event_id = $event_id;

        $this->similarity_calculation_job_record = SimilarityCalculationJobRecord::create([
                'pass_id' => $event_id,
                'type' => SimilarityCalculationJobRecord::EVENT_GIVEN_TYPE,
                'status' => SimilarityCalculationJobRecord::WAITING
            ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->similarity_calculation_job_record->toRunning();

        $this->similarityCalculation_event_given($this->event_id);

        $this->similarity_calculation_job_record->toFinished();
    }
}
