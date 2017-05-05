<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Participant;
use Mail;

class EventJoinConfirmMailSendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $participant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $participant = $this->participant;
        $user = $participant->user;
        $event = $participant->event;

        Mail::send('emails.event_join_confirm', ['user' => $user, 'event' => $event], function($message) use ($user) {
            $message->to($user->email)->subject('Volunteer activities Joined Event Reminder');
        });
    }
}
