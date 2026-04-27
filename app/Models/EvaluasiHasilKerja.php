<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiHasilKerja extends Model
{
    protected $table = 'evaluasi_hasil_kerja';

    protected $fillable = [
        'evaluasi_bulanan_id',
        'indikator_kinerja_id',
        'target_bulan',
        'realisasi',
        'capaian',
    ];

    protected $casts = [
        'realisasi' => 'decimal:2',
        'capaian' => 'decimal:2',
    ];

    public function evaluasiBulanan(): BelongsTo
    {
        return $this->belongsTo(EvaluasiBulanan::class);
    }

    public function indikatorKinerja(): BelongsTo
    {
        return $this->belongsTo(IndikatorKinerja::class);
    }

    /**
     * Hitung capaian: (Realisasi / Target Bulan) * 100
     */
    public function hitungCapaian(): float
    {
        if ($this->target_bulan == 0) {
            return 0;
        }
        return round(($this->realisasi / $this->target_bulan) * 100, 2);
    }
}
