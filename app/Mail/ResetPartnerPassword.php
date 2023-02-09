<?php

namespace App\Mail;

use App\Models\Partner;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPartnerPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $partner;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Partner $partner, $url)
    {
        $this->partner = $partner;
        $this->url = $url;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('no-reply@'.env("APP_DOMAIN"), env("APP_NAME")),
            subject: __('Forgot Your Password?') .' - '. env('APP_NAME'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.auth.reset-password',
            with: [
                'name' => $this->partner->name,
                'url' => $this->url
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
