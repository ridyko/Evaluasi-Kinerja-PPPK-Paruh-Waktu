<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PejabatPenilai extends Model
{
    protected $table = 'pejabat_penilai';

    protected $fillable = [
        'nama',
        'nip',
        'pangkat_gol',
        'jabatan',
        'unit_kerja',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function evaluasiBulanan(): HasMany
    {
        return $this->hasMany(EvaluasiBulanan::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
    }
}
