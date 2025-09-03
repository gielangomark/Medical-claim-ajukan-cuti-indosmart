<?php

namespace App\Mail;

use App\Models\Cuti;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CutiStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $cuti;
    public $remainingDays;

    public function __construct(Cuti $cuti, $remainingDays = null)
    {
        $this->cuti = $cuti;
        $this->remainingDays = $remainingDays;
    }

    public function build()
    {
        $subject = "Status Pengajuan Cuti " . ucfirst($this->cuti->status);
        
    return $this->subject($subject)
            ->markdown('emails.cuti-status-updated')
            ->with(['remainingDays' => $this->remainingDays]);
    }
}
