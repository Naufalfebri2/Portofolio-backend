<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class BackupCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $zipPath,
        public float $sizeInMb,
    ) {
    }

    public function build()
    {
        return $this->subject('Portfolio Backup — ' . now()->format('d M Y H:i'))
            ->view('emails.backup-completed')
            ->attach($this->zipPath, [
                'as' => basename($this->zipPath),
                'mime' => 'application/zip',
            ]);
    }
}
