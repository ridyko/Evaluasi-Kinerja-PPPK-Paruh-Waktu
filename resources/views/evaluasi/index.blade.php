@extends('layouts.app')
@section('title', 'Evaluasi Bulanan')

@section('content')
<div class="page-header">
    <div>
        <h1>Evaluasi Kinerja Bulanan</h1>
        <p>Kelola evaluasi kinerja bulanan pegawai PPPK</p>
    </div>
    @if(!$isPegawai)
    <a href="{{ route('evaluasi.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Evaluasi</a>
    @endif
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form action="{{ route('evaluasi.index') }}" method="GET" style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <select name="bulan" class="form-select" style="max-width: 180px;">
                <option value="">-- Semua Bulan --</option>
                @php
                    $bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                @endphp
                @foreach($bulanNames as $i => $b)
                    <option value="{{ $i + 1 }}" {{ request('bulan') == ($i + 1) ? 'selected' : '' }}>{{ $b }}</option>
                @endforeach
            </select>
            <select name="tahun" class="form-select" style="max-width: 140px;">
                <option value="">-- Tahun --</option>
                @for($y = date('Y'); $y >= 2020; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            @if(!$isPegawai)
            <select name="pegawai_id" class="form-select" style="max-width: 300px;">
                <option value="">-- Semua Pegawai --</option>
                @foreach($pegawaiList as $p)
                    <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            @endif
            <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('evaluasi.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> Reset</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($evaluasi->isEmpty())
            <div class="empty-state">
                <i class="fas fa-clipboard-check"></i>
                <p>Belum ada data evaluasi</p>
            </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pegawai</th>
                    <th>Jabatan</th>
                    <th>Periode</th>
                    <th>Hasil Kerja</th>
                    <th>Perilaku</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluasi as $i => $ev)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight: 600;">{{ $ev->pegawai->nama }}</td>
                    <td style="font-size: 0.75rem; color: var(--text-secondary);">{{ $ev->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                    <td>{{ $ev->nama_bulan }} {{ $ev->tahun }}</td>
                    <td>
                        <span style="font-weight: 700; color: {{ $ev->capaian_hasil_kerja >= 80 ? 'var(--success)' : ($ev->capaian_hasil_kerja > 0 ? 'var(--warning)' : 'var(--text-secondary)') }};">
                            {{ number_format($ev->capaian_hasil_kerja, 2) }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: {{ $ev->capaian_perilaku_kerja >= 2 ? 'var(--success)' : 'var(--warning)' }};">
                            {{ number_format($ev->capaian_perilaku_kerja, 2) }}
                        </span>
                    </td>
                    <td>
                        @if($ev->status === 'final')
                            <span class="badge badge-success">Final</span>
                        @else
                            <span class="badge badge-warning">Draft</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('evaluasi.show', $ev) }}" class="btn btn-ghost btn-sm" title="Lihat"><i class="fas fa-eye"></i></a>
                            
                            {{-- Separate Export Buttons --}}
                            <a href="{{ route('evaluasi.pdf', $ev) }}" class="btn btn-ghost btn-sm" title="Download PDF" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('evaluasi.excel', $ev) }}" class="btn btn-ghost btn-sm" title="Download Excel" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>

                            @if(!$isPegawai)
                                @if($ev->status === 'draft')
                                    <a href="{{ route('evaluasi.edit', $ev) }}" class="btn btn-ghost btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('evaluasi.destroy', $ev) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus evaluasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
                            @endif
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
