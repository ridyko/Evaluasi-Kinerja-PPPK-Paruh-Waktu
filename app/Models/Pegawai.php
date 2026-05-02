<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nama',
        'ni_pppk',
        'pangkat_gol',
        'jabatan_id',
        'unit_kerja',
        'telepon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function evaluasiBulanan(): HasMany
    {
        return $this->hasMany(EvaluasiBulanan::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class);
    }

    public function getNamaJabatanAttribute(): string
    {
        return $this->jabatan ? $this->jabatan->nama_jabatan : '-';
    }
}
