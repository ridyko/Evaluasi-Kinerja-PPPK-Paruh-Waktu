@extends('layouts.app')
@section('title', 'Indikator Kinerja')

@section('content')
<div class="page-header">
    <div>
        <h1>Indikator Kinerja Individu</h1>
        <p>Kelola indikator kinerja per jabatan</p>
    </div>
    <a href="{{ route('indikator.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Indikator</a>
</div>

{{-- Filter by jabatan --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form action="{{ route('indikator.index') }}" method="GET" style="display: flex; align-items: center; gap: 1rem;">
            <label class="form-label" style="margin: 0; white-space: nowrap;">Filter Jabatan:</label>
            <select name="jabatan_id" class="form-select" style="max-width: 400px;" onchange="this.form.submit()">
                <option value="">-- Semua Jabatan --</option>
                @foreach($jabatanList as $j)
                    <option value="{{ $j->id }}" {{ $selectedJabatan == $j->id ? 'selected' : '' }}>{{ $j->nama_jabatan }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($indikator->isEmpty())
            <div class="empty-state">
                <i class="fas fa-bullseye"></i>
                <p>Belum ada indikator kinerja</p>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jabatan</th>
                    <th>Urut</th>
                    <th>Deskripsi Indikator</th>
                    <th>Target Tahunan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($indikator as $i => $ind)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="badge badge-info">{{ $ind->jabatan->nama_jabatan ?? '-' }}</span></td>
                    <td style="text-align: center; font-weight: 700;">{{ $ind->nomor_urut }}</td>
                    <td style="font-size: 0.8rem; max-width: 400px;">{{ $ind->deskripsi }}</td>
                    <td>{{ $ind->target_tahunan }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('indikator.edit', $ind) }}" class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('indikator.destroy', $ind) }}" method="POST" onsubmit="return confirm('Yakin?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
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
