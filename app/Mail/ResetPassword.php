<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $username, public $token_link)
    {
        //
    }

    public function envelope(): Envelope{
        return new Envelope(
            from: new Address('cadastro@mail.com', 'Suporte'),
            subject: 'Confirmação de cadastro',
        );
    }

    public function content(): Content{
        return new Content(
            view: 'mail.reset_password',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
