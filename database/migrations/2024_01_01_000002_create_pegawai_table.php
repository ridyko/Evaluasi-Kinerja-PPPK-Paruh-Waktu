<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('ni_pppk')->unique();
            $table->string('pangkat_gol')->nullable();
            $table->foreignId('jabatan_id')->constrained('jabatan')->onDelete('restrict');
            $table->string('unit_kerja')->default('SMK Negeri 2 Jakarta');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
