@extends('layouts.app')
@section('title', 'Tambah Pejabat Penilai')

@section('content')
<div class="page-header">
    <div>
        <h1>Tambah Pejabat Penilai</h1>
        <p>Tambah data pejabat penilai kinerja baru</p>
    </div>
    <a href="{{ route('penilai.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="{{ route('penilai.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">NIP <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Pangkat/Gol. Ruang</label>
                <input type="text" name="pangkat_gol" class="form-control" value="{{ old('pangkat_gol') }}" placeholder="Contoh: Pembina Tk.I (IV/B)">
            </div>
            <div class="form-group">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" placeholder="Contoh: Kepala Sub Bagian Tata Usaha">
            </div>
            <div class="form-group">
                <label class="form-label">Unit Kerja <span style="color: var(--danger);">*</span></label>
                <input type="text" name="unit_kerja" class="form-control" value="{{ old('unit_kerja', config('app.organization_name')) }}" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
