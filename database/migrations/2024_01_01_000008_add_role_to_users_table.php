<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'penilai', 'pegawai'])->default('pegawai')->after('email');
            $table->foreignId('pegawai_id')->nullable()->after('role')->constrained('pegawai')->onDelete('set null');
            $table->foreignId('pejabat_penilai_id')->nullable()->after('pegawai_id')->constrained('pejabat_penilai')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            $table->dropForeign(['pejabat_penilai_id']);
            $table->dropColumn(['role', 'pegawai_id', 'pejabat_penilai_id']);
        });
    }
};
