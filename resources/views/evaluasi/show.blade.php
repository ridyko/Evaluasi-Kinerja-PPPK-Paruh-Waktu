@extends('layouts.app')
@section('title', 'Detail Evaluasi')

@section('content')
<div class="page-header">
    <div>
        <h1>Detail Evaluasi Kinerja</h1>
        <p>{{ $evaluasi->pegawai->nama }} — {{ $evaluasi->nama_bulan }} {{ $evaluasi->tahun }}</p>
    </div>
    <div class="actions">
        <a href="{{ route('evaluasi.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
        <a href="{{ route('evaluasi.pdf', $evaluasi) }}" class="btn btn-ghost" style="border: 1px solid #ef4444; color: #ef4444;"><i class="fas fa-file-pdf"></i> Export PDF</a>
        <a href="{{ route('evaluasi.excel', $evaluasi) }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
    </div>
</div>

{{-- Info Cards --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-user" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Pegawai yang Dinilai</h2></div>
        <div class="card-body">
            <table style="width: 100%; font-size: 0.85rem;">
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary); width: 120px;">Nama</td><td style="font-weight: 600;">{{ $evaluasi->pegawai->nama }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">NI PPPK</td><td>{{ $evaluasi->pegawai->ni_pppk }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Pangkat/Gol</td><td>{{ $evaluasi->pegawai->pangkat_gol ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Jabatan</td><td>{{ $evaluasi->pegawai->jabatan->nama_jabatan ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Unit Kerja</td><td>{{ $evaluasi->pegawai->unit_kerja }}</td></tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-user-tie" style="color: var(--accent); margin-right: 0.5rem;"></i>Pejabat Penilai Kinerja</h2></div>
        <div class="card-body">
            <table style="width: 100%; font-size: 0.85rem;">
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary); width: 120px;">Nama</td><td style="font-weight: 600;">{{ $evaluasi->pejabatPenilai->nama }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">NIP</td><td>{{ $evaluasi->pejabatPenilai->nip }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Pangkat/Gol</td><td>{{ $evaluasi->pejabatPenilai->pangkat_gol ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Jabatan</td><td>{{ $evaluasi->pejabatPenilai->jabatan ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Unit Kerja</td><td>{{ $evaluasi->pejabatPenilai->unit_kerja }}</td></tr>
            </table>
        </div>
    </div>
</div>

{{-- Hasil Kerja --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h2><i class="fas fa-chart-line" style="color: var(--success); margin-right: 0.5rem;"></i>Hasil Kerja</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Indikator Kinerja Individu</th>
                    <th>Target Tahunan</th>
                    <th>Target Bulan</th>
                    <th>Realisasi</th>
                    <th>Capaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluasi->hasilKerja as $i => $hk)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-size: 0.8rem;">{{ $hk->indikatorKinerja->deskripsi }}</td>
                    <td style="text-align: center;">{{ $hk->indikatorKinerja->target_tahunan }}</td>
                    <td style="text-align: center;">{{ $hk->target_bulan }}</td>
                    <td style="text-align: center;">{{ $hk->realisasi }}</td>
                    <td style="text-align: center; font-weight: 700; color: var(--success);">{{ number_format($hk->capaian, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 1rem 1.5rem; text-align: right; border-top: 1px solid rgba(99, 102, 241, 0.08);">
            <span style="color: var(--text-secondary); font-weight: 600;">Capaian Hasil Kerja Bulanan:</span>
            <span style="font-size: 1.3rem; font-weight: 800; color: var(--success); margin-left: 0.75rem;">{{ number_format($evaluasi->capaian_hasil_kerja, 2) }}</span>
        </div>
    </div>
</div>

{{-- Perilaku --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h2><i class="fas fa-star" style="color: var(--warning); margin-right: 0.5rem;"></i>Aspek Perilaku</h2>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Aspek Perilaku</th>
                    <th colspan="2">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluasi->perilaku as $i => $pr)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 500;">{{ $pr->aspek_perilaku }}</td>
                    <td>
                        @if($pr->pengkategorian === 'Diatas Ekspektasi')
                            <span class="badge badge-success">{{ $pr->pengkategorian }}</span>
                        @elseif($pr->pengkategorian === 'Sesuai Ekspektasi')
                            <span class="badge badge-warning">{{ $pr->pengkategorian }}</span>
                        @else
                            <span class="badge badge-danger">{{ $pr->pengkategorian }}</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: 800; font-size: 1.1rem;">{{ $pr->nilai }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 1rem 1.5rem; text-align: right; border-top: 1px solid rgba(99, 102, 241, 0.08);">
            <span style="color: var(--text-secondary); font-weight: 600;">Capaian Perilaku Kerja Bulanan:</span>
            <span style="font-size: 1.3rem; font-weight: 800; color: var(--warning); margin-left: 0.75rem;">{{ number_format($evaluasi->capaian_perilaku_kerja, 2) }}</span>
        </div>
    </div>
</div>

{{-- Status --}}
<div class="card">
    <div class="card-body" style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <span style="color: var(--text-secondary);">Status:</span>
            @if($evaluasi->status === 'final')
                <span class="badge badge-success" style="margin-left: 0.5rem; font-size: 0.85rem; padding: 0.35rem 1rem;">FINAL</span>
            @else
                <span class="badge badge-warning" style="margin-left: 0.5rem; font-size: 0.85rem; padding: 0.35rem 1rem;">DRAFT</span>
            @endif
            @if($evaluasi->tanggal_evaluasi)
                <span style="margin-left: 1.5rem; color: var(--text-secondary); font-size: 0.85rem;">
                    Tanggal: {{ $evaluasi->tanggal_evaluasi->translatedFormat('d F Y') }}
                </span>
            @endif
        </div>
    </div>
</div>
@endsection
