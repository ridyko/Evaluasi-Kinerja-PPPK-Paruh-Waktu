@extends('layouts.app')
@section('title', 'Kelola Jabatan')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Jabatan</h1>
        <p>Kelola data jabatan pegawai PPPK</p>
    </div>
    <a href="{{ route('jabatan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Jabatan
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($jabatan->isEmpty())
            <div class="empty-state">
                <i class="fas fa-briefcase"></i>
                <p>Belum ada data jabatan</p>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Jabatan</th>
                    <th>Deskripsi</th>
                    <th>Pegawai</th>
                    <th>Indikator</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jabatan as $i => $j)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600;">{{ $j->nama_jabatan }}</td>
                    <td style="color: var(--text-secondary); font-size: 0.8rem;">{{ $j->deskripsi ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $j->pegawai_count }} orang</span></td>
                    <td><span class="badge badge-success">{{ $j->indikator_kinerja_count }} indikator</span></td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('jabatan.edit', $j) }}" class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('jabatan.destroy', $j) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jabatan ini?')">
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
