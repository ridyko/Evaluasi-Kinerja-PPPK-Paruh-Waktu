<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_hasil_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_bulanan_id')->constrained('evaluasi_bulanan')->onDelete('cascade');
            $table->foreignId('indikator_kinerja_id')->constrained('indikator_kinerja')->onDelete('restrict');
            $table->integer('target_bulan')->default(1);
            $table->decimal('realisasi', 8, 2)->default(0);
            $table->decimal('capaian', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_hasil_kerja');
    }
};
