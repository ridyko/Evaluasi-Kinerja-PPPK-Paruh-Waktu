@extends('layouts.app')
@section('title', 'Kelola Akun')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Akun</h1>
        <p>Kelola akun login pegawai dan pejabat penilai. Password default: NI PPPK / NIP.</p>
    </div>
    <div class="actions">
        <a href="{{ route('akun.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Akun Pegawai</a>
    </div>
</div>

{{-- Statistik --}}
@php
    $totalPegawai = $pegawai->count();
    $sudahPunyaAkunPeg = $pegawai->filter(fn($p) => $p->user !== null)->count();
    $totalPenilai = $penilai->count();
    $sudahPunyaAkunPen = $penilai->filter(fn($p) => $p->user !== null)->count();
    $totalAkun = $sudahPunyaAkunPeg + $sudahPunyaAkunPen;
    $totalSemua = $totalPegawai + $totalPenilai;
@endphp
<div class="grid-4" style="margin-bottom: 1.5rem;">
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-value">{{ $totalSemua }}</div>
        <div class="stat-label">Total Personel</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-user-check"></i></div>
        <div class="stat-value">{{ $totalAkun }}</div>
        <div class="stat-label">Sudah Punya Akun</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon"><i class="fas fa-user-times"></i></div>
        <div class="stat-value">{{ $totalSemua - $totalAkun }}</div>
        <div class="stat-label">Belum Punya Akun</div>
    </div>
    <div class="stat-card cyan">
        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
        <div class="stat-value">{{ $totalPenilai }}</div>
        <div class="stat-label">Pejabat Penilai</div>
    </div>
</div>

{{-- TABEL PEJABAT PENILAI --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h2><i class="fas fa-user-tie" style="color: var(--accent); margin-right: 0.5rem;"></i>Akun Pejabat Penilai</h2>
        <span style="font-size: 0.75rem; color: var(--text-secondary);">Password default: NIP</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($penilai->isEmpty())
            <div class="empty-state">
                <i class="fas fa-user-tie"></i>
                <p>Belum ada data pejabat penilai</p>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Email Login</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilai as $i => $pn)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600;">{{ $pn->nama }}</td>
                    <td style="font-family: monospace; font-size: 0.8rem;">{{ $pn->nip }}</td>
                    <td style="font-size: 0.8rem;">{{ $pn->jabatan ?? '-' }}</td>
                    <td>
                        @if($pn->user)
                            <span style="font-size: 0.85rem;">{{ $pn->user->email }}</span>
                        @else
                            <span style="color: var(--text-secondary); font-style: italic; font-size: 0.8rem;">— belum ada akun —</span>
                        @endif
                    </td>
                    <td>
                        @if($pn->user)
                            <span class="badge badge-success"><i class="fas fa-check-circle" style="margin-right: 4px;"></i> Aktif</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times-circle" style="margin-right: 4px;"></i> Belum Ada</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($pn->user)
                                <a href="{{ route('akun.edit', $pn->user) }}" class="btn btn-ghost btn-sm" title="Edit Akun">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('akun.resetPassword', $pn->user) }}" method="POST"
                                      onsubmit="return confirm('Reset password {{ $pn->nama }} ke NIP ({{ $pn->nip }})?')">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" title="Reset Password ke NIP" style="color: #fff;">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                                <form action="{{ route('akun.destroy', $pn->user) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus akun {{ $pn->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Akun">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 0.75rem; color: var(--text-secondary);">Tambah via Seeder</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

{{-- TABEL PEGAWAI --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Akun Pegawai PPPK</h2>
        <span style="font-size: 0.75rem; color: var(--text-secondary);">Password default: NI PPPK</span>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($pegawai->isEmpty())
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>Belum ada data pegawai</p>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>NI PPPK</th>
                    <th>Jabatan</th>
                    <th>Email Login</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600;">{{ $p->nama }}</td>
                    <td style="font-family: monospace; font-size: 0.8rem;">{{ $p->ni_pppk }}</td>
                    <td style="font-size: 0.75rem;">
                        <span class="badge badge-info">{{ $p->jabatan->nama_jabatan ?? '-' }}</span>
                    </td>
                    <td>
                        @if($p->user)
                            <span style="font-size: 0.85rem;">{{ $p->user->email }}</span>
                        @else
                            <span style="color: var(--text-secondary); font-style: italic; font-size: 0.8rem;">— belum ada akun —</span>
                        @endif
                    </td>
                    <td>
                        @if($p->user)
                            <span class="badge badge-success"><i class="fas fa-check-circle" style="margin-right: 4px;"></i> Aktif</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times-circle" style="margin-right: 4px;"></i> Belum Ada</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            @if($p->user)
                                <a href="{{ route('akun.edit', $p->user) }}" class="btn btn-ghost btn-sm" title="Edit Akun">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('akun.resetPassword', $p->user) }}" method="POST"
                                      onsubmit="return confirm('Reset password {{ $p->nama }} ke NI PPPK ({{ $p->ni_pppk }})?')">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" title="Reset Password ke NI PPPK" style="color: #fff;">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                                <form action="{{ route('akun.destroy', $p->user) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus akun {{ $p->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Akun">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('akun.create') }}?pegawai_id={{ $p->id }}" class="btn btn-success btn-sm" title="Buat Akun">
                                    <i class="fas fa-user-plus"></i> Buat Akun
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
