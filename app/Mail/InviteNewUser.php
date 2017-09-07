<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteNewUser extends Mailable
{
    use Queueable, SerializesModels;

    #region PROPERTIES
    public $creator;
    public $receptor;
    #endregion

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $creator, User $receptor)
    {
        $this->creator = $creator;
        $this->receptor = $receptor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.invite-new-user');
    }
}
