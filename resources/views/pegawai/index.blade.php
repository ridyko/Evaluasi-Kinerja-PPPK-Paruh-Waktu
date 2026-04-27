@extends('layouts.app')
@section('title', 'Kelola Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h1>Kelola Pegawai</h1>
        <p>Kelola data pegawai PPPK</p>
    </div>
    <a href="{{ route('pegawai.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pegawai</a>
</div>

<div class="card">
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
                    <th>Nama</th>
                    <th>NI PPPK</th>
                    <th>Pangkat/Gol</th>
                    <th>Jabatan</th>
                    <th>Unit Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawai as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600;">{{ $p->nama }}</td>
                    <td style="font-family: monospace; font-size: 0.8rem;">{{ $p->ni_pppk }}</td>
                    <td>{{ $p->pangkat_gol ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $p->jabatan->nama_jabatan ?? '-' }}</span></td>
                    <td style="font-size: 0.8rem;">{{ $p->unit_kerja }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('pegawai.edit', $p) }}" class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('pegawai.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
