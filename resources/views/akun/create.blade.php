@extends('layouts.app')
@section('title', 'Buat Akun Pegawai')

@section('content')
<div class="page-header">
    <div>
        <h1>Buat Akun Pegawai</h1>
        <p>Buat akun login untuk pegawai. Password default: NI PPPK masing-masing.</p>
    </div>
    <a href="{{ route('akun.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

@if($pegawai->isEmpty())
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-user-check"></i>
                <p>Semua pegawai sudah memiliki akun!</p>
            </div>
        </div>
    </div>
@else

{{-- Buat Akun Satu per Satu --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h2><i class="fas fa-user-plus" style="color: var(--success); margin-right: 0.5rem;"></i>Buat Akun Satu Pegawai</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('akun.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Pegawai <span style="color: var(--danger);">*</span></label>
                    <select name="pegawai_id" class="form-select" required id="selectPegawai">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawai as $p)
                            <option value="{{ $p->id }}" data-nippk="{{ $p->ni_pppk }}"
                                {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} — {{ $p->ni_pppk }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Login <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="email@contoh.com" value="{{ old('email') }}">
                </div>
            </div>
            <div style="padding: 0.75rem 1rem; background: rgba(6, 182, 212, 0.06); border: 1px solid rgba(6, 182, 212, 0.1); border-radius: 10px; margin-bottom: 1rem;">
                <span style="color: var(--accent); font-size: 0.8rem;">
                    <i class="fas fa-info-circle"></i>
                    Password akan diset otomatis ke <strong>NI PPPK</strong> pegawai yang dipilih.
                </span>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Buat Akun</button>
        </form>
    </div>
</div>

{{-- Buat Akun Semua Sekaligus --}}
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Buat Akun Semua Pegawai Sekaligus</h2>
        <span class="badge badge-warning">{{ $pegawai->count() }} pegawai belum punya akun</span>
    </div>
    <div class="card-body">
        <form action="{{ route('akun.createAll') }}" method="POST" id="formBulk">
            @csrf
            <div style="padding: 0.75rem 1rem; background: rgba(245, 158, 11, 0.06); border: 1px solid rgba(245, 158, 11, 0.1); border-radius: 10px; margin-bottom: 1.25rem;">
                <span style="color: var(--warning); font-size: 0.8rem;">
                    <i class="fas fa-exclamation-triangle"></i>
                    Isi email untuk setiap pegawai di bawah ini, lalu klik "Buat Semua Akun". Password = NI PPPK.
                </span>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">
                            <input type="checkbox" id="checkAll" checked style="accent-color: var(--primary);">
                        </th>
                        <th>Nama</th>
                        <th>NI PPPK</th>
                        <th>Email Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $i => $p)
                    <tr>
                        <td>
                            <input type="checkbox" class="bulk-check" data-index="{{ $i }}" checked
                                   style="accent-color: var(--primary);">
                        </td>
                        <td style="font-weight: 600;">{{ $p->nama }}</td>
                        <td style="font-family: monospace; font-size: 0.8rem;">{{ $p->ni_pppk }}</td>
                        <td>
                            <input type="hidden" name="accounts[{{ $i }}][pegawai_id]" value="{{ $p->id }}" class="bulk-input-{{ $i }}">
                            <input type="email" name="accounts[{{ $i }}][email]" class="form-control bulk-input-{{ $i }}"
                                   placeholder="email@contoh.com" required
                                   style="padding: 0.4rem 0.75rem; font-size: 0.85rem;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 1.25rem; display: flex; align-items: center; gap: 1rem;">
                <button type="submit" class="btn btn-success" onclick="return confirm('Buat akun untuk semua pegawai yang dipilih?')">
                    <i class="fas fa-users"></i> Buat Semua Akun
                </button>
                <span style="font-size: 0.8rem; color: var(--text-secondary);">Password semua akun = NI PPPK masing-masing</span>
            </div>
        </form>
    </div>
</div>

@endif
@endsection

@section('scripts')
<script>
    // Check/uncheck all
    document.getElementById('checkAll')?.addEventListener('change', function() {
        document.querySelectorAll('.bulk-check').forEach(cb => {
            cb.checked = this.checked;
            toggleRow(cb);
        });
    });

    document.querySelectorAll('.bulk-check').forEach(cb => {
        cb.addEventListener('change', function() {
            toggleRow(this);
        });
    });

    function toggleRow(cb) {
        const index = cb.dataset.index;
        const inputs = document.querySelectorAll(`.bulk-input-${index}`);
        inputs.forEach(input => {
            input.disabled = !cb.checked;
            if (!cb.checked) {
                input.removeAttribute('required');
            } else if (input.type === 'email') {
                input.setAttribute('required', '');
            }
        });
    }
</script>
@endsection
