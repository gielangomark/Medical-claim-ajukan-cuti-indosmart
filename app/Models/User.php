<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'department',
    'gender',         // <-- Tambahkan ini
    'marital_status', // <-- Tambahkan ini
    'work_hours',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }

    // Users that can act as substitutes (simple helper)
    public function scopePotentialSubstitutes($query, $department)
    {
    return $query->where('department', $department)->orderByDesc('work_hours');
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function dataChangeRequests()
{
    return $this->hasMany(\App\Models\DataChangeRequest::class);
}
}