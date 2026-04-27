<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'pegawai_id',
        'pejabat_penilai_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pejabatPenilai(): BelongsTo
    {
        return $this->belongsTo(PejabatPenilai::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPenilai(): bool
    {
        return $this->role === 'penilai';
    }

    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }
}
