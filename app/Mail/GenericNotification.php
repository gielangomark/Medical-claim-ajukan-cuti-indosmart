<?php

// PASTIKAN NAMESPACE-NYA BENAR
namespace App\Mail; 

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// PASTIKAN NAMA KELAS & `extends Mailable` BENAR
class GenericNotification extends Mailable 
{
    use Queueable, SerializesModels;

    // Properti publik untuk menyimpan data email
    public string $greeting;
    public array $lines;
    public string $actionText;
    public string $actionUrl;
    // Optional second action (for one-click accept/decline)
    public ?string $actionText2 = null;
    public ?string $actionUrl2 = null;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(string $subject, string $greeting, array $lines, string $actionText = '', string $actionUrl = '', ?string $actionText2 = null, ?string $actionUrl2 = null)
    {
        $this->subject = $subject;
        $this->greeting = $greeting;
        $this->lines = $lines;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
        $this->actionText2 = $actionText2;
        $this->actionUrl2 = $actionUrl2;
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

    /**
     * Dapatkan lampiran untuk pesan.
     */
    public function attachments()
    {
        return [];
    }
}