<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\Group;
use Mail;

class groupFormApprovedMailSendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $group_form;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Group $group_form)
    {
        $this->user = $user;
        $this->group_form = $group_form;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $group_form = $this->group_form;

        Mail::send('emails.group_form_approved', ['user' => $user, 'group_form' => $group_form], function($message) use ($user) {
            $message->to($user->email)->subject('Group Application already Approved.');
        });
    }
}
