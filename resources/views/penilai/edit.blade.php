@extends('layouts.app')
@section('title', 'Edit Pejabat Penilai')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Pejabat Penilai</h1>
        <p>Perbarui data pejabat penilai kinerja</p>
    </div>
    <a href="{{ route('penilai.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="{{ route('penilai.update', $penilai) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $penilai->nama) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">NIP <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip', $penilai->nip) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Pangkat/Gol. Ruang</label>
                <input type="text" name="pangkat_gol" class="form-control" value="{{ old('pangkat_gol', $penilai->pangkat_gol) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $penilai->jabatan) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Unit Kerja <span style="color: var(--danger);">*</span></label>
                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', $penilai->unit_kerja) }}" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection
