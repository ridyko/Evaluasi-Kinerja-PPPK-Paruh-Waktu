@extends('layouts.app')
@section('title', 'Kelola Pejabat Penilai')

@section('content')
<div class="page-header">
    <div>
        <h1>Pejabat Penilai Kinerja</h1>
        <p>Kelola data pejabat penilai kinerja</p>
    </div>
    <a href="{{ route('penilai.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Penilai</a>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($penilai->isEmpty())
            <div class="empty-state">
                <i class="fas fa-user-tie"></i>
                <p>Belum ada data pejabat penilai</p>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Pangkat/Gol</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penilai as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600;">{{ $p->nama }}</td>
                    <td style="font-family: monospace; font-size: 0.8rem;">{{ $p->nip }}</td>
                    <td>{{ $p->pangkat_gol ?? '-' }}</td>
                    <td style="font-size: 0.8rem;">{{ $p->jabatan ?? '-' }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('penilai.edit', $p) }}" class="btn btn-ghost btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('penilai.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin?')">
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
