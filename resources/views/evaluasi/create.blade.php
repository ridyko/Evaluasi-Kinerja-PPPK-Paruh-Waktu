@extends('layouts.app')
@section('title', 'Buat Evaluasi Baru')

@section('content')
<div class="page-header">
    <div>
        <h1>Buat Evaluasi Baru</h1>
        <p>Pilih pegawai dan periode evaluasi</p>
    </div>
    <a href="{{ route('evaluasi.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="{{ route('evaluasi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Pegawai <span style="color: var(--danger);">*</span></label>
                <select name="pegawai_id" class="form-select" required>
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawai as $p)
                        <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} — {{ $p->jabatan->nama_jabatan ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Pejabat Penilai <span style="color: var(--danger);">*</span></label>
                @if(auth()->user()->isPenilai() && auth()->user()->pejabat_penilai_id)
                    {{-- Penilai sudah login, otomatis terisi --}}
                    <input type="hidden" name="pejabat_penilai_id" value="{{ auth()->user()->pejabat_penilai_id }}">
                    <div class="form-control" style="opacity: 0.7; cursor: not-allowed; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-user-tie" style="color: var(--accent);"></i>
                        {{ auth()->user()->pejabatPenilai->nama }} — {{ auth()->user()->pejabatPenilai->jabatan ?? '' }}
                    </div>
                @else
                    <select name="pejabat_penilai_id" class="form-select" required>
                        <option value="">-- Pilih Penilai --</option>
                        @foreach($penilai as $pn)
                            <option value="{{ $pn->id }}" {{ old('pejabat_penilai_id') == $pn->id ? 'selected' : '' }}>
                                {{ $pn->nama }} — {{ $pn->jabatan ?? '' }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Bulan <span style="color: var(--danger);">*</span></label>
                    <select name="bulan" class="form-select" required>
                        @php
                            $bulanNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        @endphp
                        @foreach($bulanNames as $i => $b)
                            <option value="{{ $i + 1 }}" {{ old('bulan', date('n')) == ($i + 1) ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun <span style="color: var(--danger);">*</span></label>
                    <input type="number" name="tahun" class="form-control" value="{{ old('tahun', date('Y')) }}" required min="2020" max="2099">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Buat Evaluasi</button>
        </form>
    </div>
</div>
@endsection
