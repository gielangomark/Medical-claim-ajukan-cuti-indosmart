<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';
    
    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_cuti',
        'alasan',
        'dokumen_pendukung',
        'status',
        'catatan',
        'processed_by',
    'processed_at',
    'pengganti_id',
    'pengganti_status'
    ];

    protected $casts = [
    // Use date cast for date-only fields to avoid timezone/timestamp issues
    'tanggal_mulai' => 'date',
    'tanggal_selesai' => 'date',
        'processed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function pengganti()
    {
        return $this->belongsTo(User::class, 'pengganti_id');
    }

    public function getDurationAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}
