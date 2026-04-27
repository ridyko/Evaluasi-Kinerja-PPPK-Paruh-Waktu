@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ auth()->user()->name }}! 👋</p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid-4" style="margin-bottom: 2rem;">
    @if(auth()->user()->isPegawai())
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-id-card"></i></div>
        <div class="stat-value" style="font-size: 1.1rem; line-height: 1.2;">{{ auth()->user()->pegawai->ni_pppk }}</div>
        <div class="stat-label">NI PPPK</div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
        <div class="stat-value" style="font-size: 1rem; line-height: 1.2;">{{ auth()->user()->pegawai->jabatan->nama_jabatan ?? '-' }}</div>
        <div class="stat-label">Jabatan Saya</div>
    </div>
    @else
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-value">{{ $totalPegawai }}</div>
        <div class="stat-label">Total Pegawai Aktif</div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
        <div class="stat-value">{{ $totalPenilai }}</div>
        <div class="stat-label">Pejabat Penilai</div>
    </div>
    @endif
    
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
        <div class="stat-value">{{ $totalEvaluasi }}</div>
        <div class="stat-label">{{ auth()->user()->isPegawai() ? 'Evaluasi Saya' : 'Total Evaluasi' }}</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-value">{{ $evaluasiBulanIni }}</div>
        <div class="stat-label">Evaluasi Bulan Ini</div>
    </div>
</div>

{{-- Chart & Recent --}}
<div class="grid-2">
    {{-- Monthly Chart --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-chart-bar" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Statistik Evaluasi {{ date('Y') }}</h2>
        </div>
        <div class="card-body">
            <div style="display: flex; align-items: flex-end; gap: 6px; height: 200px; padding-top: 1rem;">
                @php
                    $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    $maxVal = max(1, max($monthlyStats));
                @endphp
                @foreach($monthlyStats as $i => $val)
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;">
                    <span style="font-size: 0.65rem; color: var(--text-secondary); font-weight: 600;">{{ $val }}</span>
                    <div style="width: 100%; height: {{ $maxVal > 0 ? ($val / $maxVal * 160) : 0 }}px; min-height: 4px; background: linear-gradient(180deg, var(--primary), var(--accent)); border-radius: 4px 4px 0 0; transition: height 0.5s ease;"></div>
                    <span style="font-size: 0.6rem; color: var(--text-secondary);">{{ $bulanLabels[$i] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent evaluations --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-clock" style="color: var(--accent); margin-right: 0.5rem;"></i>Evaluasi Terbaru</h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isPenilai())
            <a href="{{ route('evaluasi.index') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
            @endif
        </div>
        <div class="card-body" style="padding: 0;">
            @if($recentEvaluasi->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada data evaluasi</p>
                </div>
            @else
                <table class="data-table">
                    <thead>
                        <tr>
                            @if(auth()->user()->isPegawai())
                                <th>Periode Evaluasi</th>
                                <th>Pejabat Penilai</th>
                            @else
                                <th>Pegawai</th>
                                <th>Periode</th>
                            @endif
                            <th>Capaian</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentEvaluasi as $ev)
                        <tr>
                            @if(auth()->user()->isPegawai())
                                <td style="font-weight: 600;">{{ $ev->nama_bulan }} {{ $ev->tahun }}</td>
                                <td style="font-size: 0.85rem;">{{ $ev->pejabatPenilai->nama }}</td>
                            @else
                                <td>
                                    <div style="font-weight: 600; font-size: 0.85rem;">{{ $ev->pegawai->nama }}</div>
                                    <div style="font-size: 0.7rem; color: var(--text-secondary);">{{ $ev->pegawai->jabatan->nama_jabatan ?? '-' }}</div>
                                </td>
                                <td>{{ $ev->nama_bulan }} {{ $ev->tahun }}</td>
                            @endif
                            <td>
                                <span style="font-weight: 700; color: {{ $ev->capaian_hasil_kerja >= 80 ? 'var(--success)' : 'var(--warning)' }};">
                                    {{ number_format($ev->capaian_hasil_kerja, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($ev->status === 'final')
                                    <span class="badge badge-success">Final</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
