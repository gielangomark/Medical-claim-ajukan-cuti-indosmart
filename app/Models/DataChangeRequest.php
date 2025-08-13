<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type',
        'new_data',
        'proof_document_path',
        'status',
        'rejection_reason',
        'processed_by',
    ];

    protected $casts = [
        'new_data' => 'array', // Otomatis konversi JSON ke array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
