<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluasiBulanan extends Model
{
    protected $table = 'evaluasi_bulanan';

    protected $fillable = [
        'pegawai_id',
        'pejabat_penilai_id',
        'bulan',
        'tahun',
        'capaian_hasil_kerja',
        'capaian_perilaku_kerja',
        'status',
        'tanggal_evaluasi',
    ];

    protected $casts = [
        'capaian_hasil_kerja' => 'decimal:2',
        'capaian_perilaku_kerja' => 'decimal:2',
        'tanggal_evaluasi' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function pejabatPenilai(): BelongsTo
    {
        return $this->belongsTo(PejabatPenilai::class);
    }

    public function hasilKerja(): HasMany
    {
        return $this->hasMany(EvaluasiHasilKerja::class);
    }

    public function perilaku(): HasMany
    {
        return $this->hasMany(EvaluasiPerilaku::class);
    }

    public function getNamaBulanAttribute(): string
    {
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulanNames[$this->bulan] ?? '-';
    }

    public function hitungCapaianHasilKerja(): float
    {
        $hasilKerja = $this->hasilKerja;
        if ($hasilKerja->isEmpty()) {
            return 0;
        }

        $totalCapaian = $hasilKerja->sum('capaian');
        return round($totalCapaian / $hasilKerja->count(), 2);
    }

    public function hitungCapaianPerilaku(): float
    {
        $perilaku = $this->perilaku;
        if ($perilaku->isEmpty()) {
            return 0;
        }

        $totalNilai = $perilaku->sum('nilai');
        return round($totalNilai / $perilaku->count(), 2);
    }
}
