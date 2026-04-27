<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_perilaku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_bulanan_id')->constrained('evaluasi_bulanan')->onDelete('cascade');
            $table->string('aspek_perilaku'); // BerAKHLAK aspects
            $table->enum('pengkategorian', ['Dibawah Ekspektasi', 'Sesuai Ekspektasi', 'Diatas Ekspektasi'])->default('Sesuai Ekspektasi');
            $table->integer('nilai')->default(2); // 1, 2, or 3
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_perilaku');
    }
};
