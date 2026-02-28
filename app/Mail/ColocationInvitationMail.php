<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ColocationInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;
    public string $url;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;

        $this->url = route('invitations.show', $invitation->token);
    }

    public function build()
    {
        return $this->subject('Invitation à rejoindre une colocation')
            ->markdown('emails.invitations.colocation');
    }
}