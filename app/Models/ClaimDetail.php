<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'claim_id',
        'transaction_date',
        'description',
        'patient_name',
        'amount',
        'proof_file_path',
    ];

    // ... (Relasi yang sudah ada di bawah sini)
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}