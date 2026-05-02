<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaLog extends Model
{
    protected $fillable = ['evaluasi_id', 'nomor_tujuan', 'pesan', 'status', 'keterangan'];

    public function evaluasi()
    {
        return $this->belongsTo(EvaluasiBulanan::class, 'evaluasi_id');
    }
}
