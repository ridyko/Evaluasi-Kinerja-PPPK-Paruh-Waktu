@extends('layouts.app')
@section('title', 'Edit Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Pegawai</h1>
        <p>Perbarui data pegawai</p>
    </div>
    <a href="{{ route('pegawai.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="{{ route('pegawai.update', $pegawai) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">NI PPPK <span style="color: var(--danger);">*</span></label>
                <input type="text" name="ni_pppk" class="form-control" value="{{ old('ni_pppk', $pegawai->ni_pppk) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Pangkat/Gol. Ruang</label>
                <input type="text" name="pangkat_gol" class="form-control" value="{{ old('pangkat_gol', $pegawai->pangkat_gol) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Jabatan <span style="color: var(--danger);">*</span></label>
                <select name="jabatan_id" class="form-select" required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatan as $j)
                        <option value="{{ $j->id }}" {{ old('jabatan_id', $pegawai->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Unit Kerja <span style="color: var(--danger);">*</span></label>
                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $pegawai->unit_kerja) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">No. WhatsApp <small>(Contoh: 08123456789)</small></label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $pegawai->telepon) }}">
                <small style="color: var(--text-secondary); font-size: 0.75rem;">Digunakan untuk notifikasi finalisasi evaluasi.</small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection
