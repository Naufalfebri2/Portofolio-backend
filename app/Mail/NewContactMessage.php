<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Message $contactMessage)
    {
    }

    public function build()
    {
        return $this->subject('Pesan Baru dari Portfolio: ' . ($this->contactMessage->subject ?: 'Tanpa Subjek'))
            ->view('emails.contact-message');
    }
}
