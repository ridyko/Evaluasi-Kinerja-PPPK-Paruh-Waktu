@extends('layouts.app')
@section('title', 'Edit Jabatan')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Jabatan</h1>
        <p>Perbarui data jabatan</p>
    </div>
    <a href="{{ route('jabatan.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="{{ route('jabatan.update', $jabatan) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Jabatan <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_jabatan" class="form-control" value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $jabatan->deskripsi) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection
