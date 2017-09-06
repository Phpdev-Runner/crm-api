<?php

namespace App\Jobs;

use App\Mail\InviteNewUser;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    #region PROPERTIES
    public $receptor;
    public $emailObject;
    #endregion

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(String $receptor, Mailable $emailObject)
    {
        $this->receptor = $receptor;
        $this->emailObject = $emailObject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->receptor)->send($this->emailObject);
    }
}
