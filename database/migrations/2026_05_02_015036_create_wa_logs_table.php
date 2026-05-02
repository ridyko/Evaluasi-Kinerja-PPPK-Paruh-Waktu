<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wa_logs', function (Blueprint $row) {
            $row->id();
            $row->foreignId('evaluasi_id')->constrained('evaluasi_bulanan')->onDelete('cascade');
            $row->string('nomor_tujuan');
            $row->text('pesan');
            $row->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $row->text('keterangan')->nullable();
            $row->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wa_logs');
    }
};
