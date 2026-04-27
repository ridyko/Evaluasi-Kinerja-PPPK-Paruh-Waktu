<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluasiPerilaku extends Model
{
    protected $table = 'evaluasi_perilaku';

    protected $fillable = [
        'evaluasi_bulanan_id',
        'aspek_perilaku',
        'pengkategorian',
        'nilai',
    ];

    public function evaluasiBulanan(): BelongsTo
    {
        return $this->belongsTo(EvaluasiBulanan::class);
    }

    /**
     * Set nilai berdasarkan pengkategorian
     */
    public static function nilaiDariKategori(string $kategori): int
    {
        return match ($kategori) {
            'Diatas Ekspektasi' => 3,
            'Sesuai Ekspektasi' => 2,
            'Dibawah Ekspektasi' => 1,
            default => 2,
        };
    }

    /**
     * Daftar 7 aspek perilaku BerAKHLAK
     */
    public static function daftarAspek(): array
    {
        return [
            'Berorientasi Pelayanan',
            'Akuntabel',
            'Kompeten',
            'Harmonis',
            'Loyal',
            'Adaptif',
            'Kolaboratif',
        ];
    }
}
