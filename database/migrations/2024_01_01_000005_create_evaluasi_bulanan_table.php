<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('pejabat_penilai_id')->constrained('pejabat_penilai')->onDelete('restrict');
            $table->integer('bulan'); // 1-12
            $table->integer('tahun');
            $table->decimal('capaian_hasil_kerja', 8, 2)->default(0);
            $table->decimal('capaian_perilaku_kerja', 8, 2)->default(0);
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->date('tanggal_evaluasi')->nullable();
            $table->timestamps();

            $table->unique(['pegawai_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_bulanan');
    }
};
