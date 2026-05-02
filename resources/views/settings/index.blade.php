@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="page-header">
    <div>
        <h1>Pengaturan Sistem</h1>
        <p>Kelola identitas aplikasi dan organisasi Anda secara real-time.</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h2>Branding Aplikasi</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid-2" style="margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Logo Aplikasi</label>
                        <input type="file" name="app_logo" class="form-control" accept="image/*">
                        <small style="color: var(--text-secondary); font-size: 0.7rem;">Rekomendasi: PNG transparan, rasio 1:1.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Favicon (Ikon Tab)</label>
                        <input type="file" name="app_favicon" class="form-control" accept="image/*">
                        <small style="color: var(--text-secondary); font-size: 0.7rem;">Format .ico atau .png kecil.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Aplikasi</label>
                    <input type="text" name="app_name" class="form-control" value="{{ old('app_name', get_setting('app_name')) }}" required>
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Contoh: E-Kinerja, EVAKIN, dsb.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Organisasi / Instansi</label>
                    <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name', get_setting('organization_name')) }}" required>
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Contoh: SMK Negeri 2 Jakarta.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Slogan Organisasi</label>
                    <input type="text" name="organization_slogan" class="form-control" value="{{ old('organization_slogan', get_setting('organization_slogan')) }}">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Muncul di halaman login.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Teks Footer</label>
                    <input type="text" name="organization_footer" class="form-control" value="{{ old('organization_footer', get_setting('organization_footer')) }}">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Teks hak cipta di bagian bawah halaman.</small>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Pratinjau Branding</h2>
        </div>
        <div class="card-body" style="background: rgba(15, 23, 42, 0.4); border-radius: 12px; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 300px;">
            @if(get_setting('app_logo'))
                <img src="{{ asset(get_setting('app_logo')) }}" alt="Logo" style="width: 80px; height: 80px; border-radius: 16px; object-fit: contain; margin-bottom: 1.5rem; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);">
            @else
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), var(--accent)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.5rem; color: #fff; margin-bottom: 1.5rem; box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);">
                    {{ substr(get_setting('app_name'), 0, 2) }}
                </div>
            @endif
            <h3 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">{{ get_setting('app_name') }}</h3>
            <p style="color: var(--text-secondary); text-align: center; font-size: 0.9rem;">{{ get_setting('organization_name') }}</p>
            <div style="margin-top: 2rem; padding: 0.75rem 1.5rem; background: rgba(99, 102, 241, 0.1); border: 1px dashed rgba(99, 102, 241, 0.3); border-radius: 10px; font-size: 0.75rem; color: var(--text-secondary);">
                {{ get_setting('organization_footer') }}
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // System settings specific scripts if any
</script>
@endsection
@endsection
