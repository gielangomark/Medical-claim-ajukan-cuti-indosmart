<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericNotification extends Mailable
{
    use Queueable, SerializesModels;

    // PASTIKAN PROPERTI PUBLIK INI ADA
    public string $greeting;
    public array $lines; // <-- HARUS BERUPA ARRAY
    public string $actionText;
    public string $actionUrl;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(string $subject, string $greeting, array $lines, string $actionText, string $actionUrl)
    {
        $this->subject = $subject;
        $this->greeting = $greeting;
        $this->lines = $lines; // <-- PASTIKAN VARIABEL DITERIMA DI SINI
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
    }

    /**
     * Dapatkan "amplop" pesan.
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Dapatkan konten pesan.
     */
    public function content()
    {
        return new Content(
            view: 'emails.notification', // Mengarah ke template email Anda
        );
    }
}