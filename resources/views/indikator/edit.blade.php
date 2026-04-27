@extends('layouts.app')
@section('title', 'Edit Indikator Kinerja')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Indikator Kinerja</h1>
        <p>Perbarui indikator kinerja individu</p>
    </div>
    <a href="{{ route('indikator.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-body">
        <form action="{{ route('indikator.update', $indikator) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Jabatan <span style="color: var(--danger);">*</span></label>
                <select name="jabatan_id" class="form-select" required>
                    @foreach($jabatan as $j)
                        <option value="{{ $j->id }}" {{ old('jabatan_id', $indikator->jabatan_id) == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Urut <span style="color: var(--danger);">*</span></label>
                <input type="number" name="nomor_urut" class="form-control" value="{{ old('nomor_urut', $indikator->nomor_urut) }}" required min="1" style="max-width: 120px;">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Indikator <span style="color: var(--danger);">*</span></label>
                <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $indikator->deskripsi) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Target Tahunan <span style="color: var(--danger);">*</span></label>
                <input type="text" name="target_tahunan" class="form-control" value="{{ old('target_tahunan', $indikator->target_tahunan) }}" required style="max-width: 300px;">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui</button>
        </form>
    </div>
</div>
@endsection
