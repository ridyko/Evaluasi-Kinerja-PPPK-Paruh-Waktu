@extends('layouts.app')
@section('title', 'Tambah Jabatan')

@section('content')
<div class="page-header">
    <div>
        <h1>Tambah Jabatan</h1>
        <p>Tambah data jabatan baru</p>
    </div>
    <a href="{{ route('jabatan.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="{{ route('jabatan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Jabatan <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_jabatan" class="form-control" value="{{ old('nama_jabatan') }}" required placeholder="Contoh: Laboran">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi jabatan (opsional)">{{ old('deskripsi') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
