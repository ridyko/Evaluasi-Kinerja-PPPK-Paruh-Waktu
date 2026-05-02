@extends('layouts.app')

@section('title', 'WhatsApp Gateway')

@section('content')
<div class="page-header">
    <div>
        <h1>WhatsApp Gateway</h1>
        <p>Kelola layanan pengiriman notifikasi otomatis ke pegawai.</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h2>Konfigurasi & Kontrol</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Gateway URL</label>
                    <input type="text" name="wa_gateway_url" class="form-control" value="{{ old('wa_gateway_url', get_setting('wa_gateway_url')) }}" placeholder="http://localhost:3000">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">URL API gateway internal.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Gateway Token</label>
                    <input type="password" name="wa_gateway_token" class="form-control" value="{{ old('wa_gateway_token', get_setting('wa_gateway_token')) }}" placeholder="Token Rahasia">
                    <small style="color: var(--text-secondary); font-size: 0.75rem;">Token keamanan untuk akses API.</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1.5rem;">
                    <i class="fas fa-save"></i> Simpan Konfigurasi
                </button>
            </form>

            <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid rgba(255,255,255,0.1);">

            <div style="background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h4 style="margin: 0; font-size: 0.9rem;">Status Layanan</h4>
                    <span id="wa-status" class="badge badge-warning">Mengecek...</span>
                </div>
                <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <button type="button" id="btn-wa-start" class="btn btn-success btn-sm" style="flex: 1;"><i class="fas fa-play"></i> Jalankan</button>
                    <button type="button" id="btn-wa-stop" class="btn btn-danger btn-sm" style="flex: 1;"><i class="fas fa-stop"></i> Matikan</button>
                </div>
                <button type="button" id="btn-wa-install" class="btn btn-ghost btn-sm" style="width: 100%;"><i class="fas fa-download"></i> Install Dependensi</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Koneksi WhatsApp</h2>
        </div>
        <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 300px;">
            <div id="wa-qr-container" style="display: none; text-align: center;">
                <div id="wa-qr-code" style="background: #fff; padding: 15px; display: inline-block; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.3);"></div>
                <p style="margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                    Buka WhatsApp di HP Anda > Menu/Setelan > Perangkat Tertaut > Tautkan Perangkat.
                </p>
            </div>
            <div id="wa-connected-state" style="display: none; text-align: center;">
                <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1.5rem;">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3 style="color: var(--success);">WhatsApp Terhubung!</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Sistem siap mengirimkan notifikasi otomatis.</p>
            </div>
            <div id="wa-loading-state" style="text-align: center;">
                <i class="fas fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--primary); opacity: 0.5;"></i>
                <p style="margin-top: 1rem; color: var(--text-secondary);">Menunggu status layanan...</p>
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
    const connectedState = document.getElementById('wa-connected-state');
    const loadingState = document.getElementById('wa-loading-state');
    const qrCodeDiv = document.getElementById('wa-qr-code');
    let qrGenerator = null;

    function checkStatus() {
        fetch('{{ route('wa.status') }}')
            .then(res => res.json())
            .then(data => {
                loadingState.style.display = 'none';
                if (data.running) {
                    if (data.connected) {
                        waStatus.innerText = 'Terhubung';
                        waStatus.className = 'badge badge-success';
                        qrContainer.style.display = 'none';
                        connectedState.style.display = 'block';
                        btnStart.disabled = true;
                        btnStop.disabled = false;
                    } else {
                        waStatus.innerText = 'Menunggu Login';
                        waStatus.className = 'badge badge-warning';
                        qrContainer.style.display = 'block';
                        connectedState.style.display = 'none';
                        btnStart.disabled = true;
                        btnStop.disabled = false;
                        fetchQR();
                    }
                } else {
                    waStatus.innerText = 'Tidak Berjalan';
                    waStatus.className = 'badge badge-danger';
                    qrContainer.style.display = 'none';
                    connectedState.style.display = 'none';
                    loadingState.style.display = 'block';
                    loadingState.innerHTML = '<i class="fas fa-power-off" style="font-size: 2rem; opacity: 0.2;"></i><p style="margin-top: 1rem; color: var(--text-secondary);">Layanan belum dijalankan.</p>';
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
                    new QRCode(qrCodeDiv, {
                        text: data.qr,
                        width: 240,
                        height: 240
                    });
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
