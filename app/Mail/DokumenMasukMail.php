<?php

namespace App\Mail;

use App\Models\Dokumen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DokumenMasukMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dokumen;

    /**
     * Create a new message instance.
     */
    public function __construct(Dokumen $dokumen)
    {
        $this->dokumen = $dokumen;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dokumen Masuk: ' . $this->dokumen->judul,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dokumen_masuk',
            with: ['dokumen' => $this->dokumen],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        if ($this->dokumen->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->dokumen->file_path)) {
            $attachments[] = Attachment::fromPath(\Illuminate\Support\Facades\Storage::disk('public')->path($this->dokumen->file_path))
                ->as($this->dokumen->file_name ?? 'dokumen.pdf')
                ->withMime($this->dokumen->file_type === 'pdf' ? 'application/pdf' : 'application/msword');
        }

        return $attachments;
    }
}
