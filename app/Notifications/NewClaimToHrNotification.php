<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Claim;

class NewClaimToHrNotification extends Notification
{
    use Queueable;

    protected $claim;

    /**
     * Create a new notification instance.
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->claim->user;
        $subject = "Pengajuan Klaim Baru dari {$employee->name}";
        $greeting = "Halo Tim HRD,";
        $line = "Anda telah menerima pengajuan klaim baru dari karyawan {$employee->name} (NIK: {$employee->nik}) sebesar Rp ".number_format($this->claim->total_amount, 0, ',', '.').".";

        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($line)
                    ->action('Proses Klaim Sekarang', route('hrd.claims.show', $this->claim))
                    ->line('Harap segera ditinjau dan diproses.');
    }
}