@extends('layouts.app')
@section('title', 'Edit Akun')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Akun</h1>
        <p>Perbarui data akun {{ $akun->name }}</p>
    </div>
    <a href="{{ route('akun.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="grid-2">
    {{-- Edit Form --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-edit" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Edit Data Akun</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('akun.update', $akun) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $akun->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Login <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $akun->email) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="{{ ucfirst($akun->role) }}" disabled
                           style="opacity: 0.6; cursor: not-allowed;">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </form>
        </div>
    </div>

    {{-- Info & Actions --}}
    <div>
        {{-- Info Pegawai --}}
        @if($akun->pegawai)
        <div class="card" style="margin-bottom: 1rem;">
            <div class="card-header">
                <h2><i class="fas fa-user" style="color: var(--accent); margin-right: 0.5rem;"></i>Info Pegawai</h2>
            </div>
            <div class="card-body">
                <table style="width: 100%; font-size: 0.85rem;">
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary); width: 100px;">Nama</td>
                        <td style="font-weight: 600;">{{ $akun->pegawai->nama }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">NI PPPK</td>
                        <td style="font-family: monospace;">{{ $akun->pegawai->ni_pppk }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">Jabatan</td>
                        <td>{{ $akun->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">Unit Kerja</td>
                        <td>{{ $akun->pegawai->unit_kerja }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        {{-- Info Penilai --}}
        @if($akun->pejabatPenilai)
        <div class="card" style="margin-bottom: 1rem;">
            <div class="card-header">
                <h2><i class="fas fa-user-tie" style="color: var(--accent); margin-right: 0.5rem;"></i>Info Pejabat Penilai</h2>
            </div>
            <div class="card-body">
                <table style="width: 100%; font-size: 0.85rem;">
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary); width: 100px;">Nama</td>
                        <td style="font-weight: 600;">{{ $akun->pejabatPenilai->nama }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">NIP</td>
                        <td style="font-family: monospace;">{{ $akun->pejabatPenilai->nip }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">Pangkat/Gol</td>
                        <td>{{ $akun->pejabatPenilai->pangkat_gol ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">Jabatan</td>
                        <td>{{ $akun->pejabatPenilai->jabatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.3rem 0; color: var(--text-secondary);">Unit Kerja</td>
                        <td>{{ $akun->pejabatPenilai->unit_kerja }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        {{-- Reset Password --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-key" style="color: var(--warning); margin-right: 0.5rem;"></i>Reset Password</h2>
            </div>
            <div class="card-body">
                @if($akun->pegawai)
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    Reset password ke <strong style="color: var(--text-primary);">NI PPPK</strong>
                    (<code style="color: var(--accent); background: rgba(6, 182, 212, 0.1); padding: 2px 8px; border-radius: 4px;">{{ $akun->pegawai->ni_pppk }}</code>)
                </p>
                <form action="{{ route('akun.resetPassword', $akun) }}" method="POST"
                      onsubmit="return confirm('Reset password {{ $akun->name }} ke NI PPPK?')">
                    @csrf
                    <button type="submit" class="btn btn-warning" style="color: #fff;">
                        <i class="fas fa-key"></i> Reset Password ke NI PPPK
                    </button>
                </form>
                @elseif($akun->pejabatPenilai)
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    Reset password ke <strong style="color: var(--text-primary);">NIP</strong>
                    (<code style="color: var(--accent); background: rgba(6, 182, 212, 0.1); padding: 2px 8px; border-radius: 4px;">{{ $akun->pejabatPenilai->nip }}</code>)
                </p>
                <form action="{{ route('akun.resetPassword', $akun) }}" method="POST"
                      onsubmit="return confirm('Reset password {{ $akun->name }} ke NIP?')">
                    @csrf
                    <button type="submit" class="btn btn-warning" style="color: #fff;">
                        <i class="fas fa-key"></i> Reset Password ke NIP
                    </button>
                </form>
                @else
                <p style="font-size: 0.85rem; color: var(--text-secondary);">
                    Akun ini tidak terkait dengan pegawai atau penilai.
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
