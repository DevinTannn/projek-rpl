<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $username;

    public function __construct(string $otp, string $username)
    {
        $this->otp      = $otp;
        $this->username = $username;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset Password – SELUNA',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }
}
