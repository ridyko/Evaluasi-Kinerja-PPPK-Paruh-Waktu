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

                <hr style="margin: 2rem 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">
                <h3 style="font-size: 1.1rem; margin-bottom: 1.5rem;"><i class="fab fa-whatsapp"></i> WhatsApp Gateway (Self-Hosted)</h3>

                <div class="form-group">
                    <label class="form-label">Gateway URL</label>
                    <input type="text" name="wa_gateway_url" class="form-control" value="{{ old('wa_gateway_url', get_setting('wa_gateway_url')) }}" placeholder="http://localhost:3000">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">URL API gateway buatan sendiri.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Gateway Token</label>
                    <input type="password" name="wa_gateway_token" class="form-control" value="{{ old('wa_gateway_token', get_setting('wa_gateway_token')) }}" placeholder="Token Rahasia">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Token keamanan untuk akses API.</small>
                </div>

                <div style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; margin-top: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h4 style="margin: 0; font-size: 0.9rem;">Status Layanan WhatsApp</h4>
                        <span id="wa-status" class="badge badge-warning">Mengecek...</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="button" id="btn-wa-start" class="btn btn-success btn-sm" style="flex: 1;"><i class="fas fa-play"></i> Jalankan</button>
                        <button type="button" id="btn-wa-stop" class="btn btn-danger btn-sm" style="flex: 1;"><i class="fas fa-stop"></i> Matikan</button>
                    </div>
                    <button type="button" id="btn-wa-install" class="btn btn-ghost btn-sm" style="width: 100%; margin-top: 0.5rem;"><i class="fas fa-download"></i> Install Dependensi</button>
                    <div id="wa-qr-container" style="display: none; margin-top: 1.5rem; text-align: center;">
                        <p style="font-size: 0.8rem; margin-bottom: 0.5rem;">Scan QR Code untuk Menghubungkan:</p>
                        <div id="wa-qr-code" style="background: #fff; padding: 10px; display: inline-block; border-radius: 8px;"></div>
                    </div>
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
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const waStatus = document.getElementById('wa-status');
    const btnStart = document.getElementById('btn-wa-start');
    const btnStop = document.getElementById('btn-wa-stop');
    const qrContainer = document.getElementById('wa-qr-container');
    const qrCodeDiv = document.getElementById('wa-qr-code');
    let qrGenerator = null;

    function checkStatus() {
        fetch('{{ route('wa.status') }}')
            .then(res => res.json())
            .then(data => {
                if (data.running) {
                    if (data.connected) {
                        waStatus.innerText = 'Terhubung';
                        waStatus.className = 'badge badge-success';
                        qrContainer.style.display = 'none';
                        btnStart.disabled = true;
                        btnStop.disabled = false;
                    } else {
                        waStatus.innerText = 'Menunggu Login';
                        waStatus.className = 'badge badge-warning';
                        qrContainer.style.display = 'block';
                        btnStart.disabled = true;
                        btnStop.disabled = false;
                        fetchQR();
                    }
                } else {
                    waStatus.innerText = 'Tidak Berjalan';
                    waStatus.className = 'badge badge-danger';
                    qrContainer.style.display = 'none';
                    btnStart.disabled = false;
                    btnStop.disabled = true;
                }
            });
    }

    function fetchQR() {
        fetch('{{ route('wa.qr') }}')
            .then(res => res.json())
            .then(data => {
                if (data.status && data.qr) {
                    qrCodeDiv.innerHTML = '';
                    if (!qrGenerator) {
                        qrGenerator = new QRCode(qrCodeDiv, {
                            text: data.qr,
                            width: 200,
                            height: 200
                        });
                    } else {
                        qrGenerator.clear();
                        qrGenerator.makeCode(data.qr);
                    }
                }
            });
    }

    btnStart.addEventListener('click', () => {
        waStatus.innerText = 'Memulai...';
        fetch('{{ route('wa.start') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            setTimeout(checkStatus, 3000);
        });
    });

    btnStop.addEventListener('click', () => {
        waStatus.innerText = 'Menghentikan...';
        fetch('{{ route('wa.stop') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            setTimeout(checkStatus, 1000);
        });
    });

    document.getElementById('btn-wa-install').addEventListener('click', function() {
        this.disabled = true;
        this.innerText = 'Menginstall...';
        fetch('{{ route('wa.install') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            alert('Proses instalasi berjalan di background. Silakan tunggu beberapa saat.');
        });
    });

    setInterval(checkStatus, 5000);
    checkStatus();
</script>
@endsection
