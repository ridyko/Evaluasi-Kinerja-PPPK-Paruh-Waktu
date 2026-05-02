@extends('layouts.app')

@section('title', 'WhatsApp Gateway')

@section('content')
<div class="page-header">
    <div>
        <h1>WhatsApp Gateway</h1>
        <p>Hubungkan sistem ke WhatsApp untuk pengiriman notifikasi otomatis.</p>
    </div>
</div>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <div class="card-body" style="text-align: center; padding: 3rem 2rem;">
            {{-- Status Header --}}
            <div id="wa-loading-state">
                <i class="fas fa-circle-notch fa-spin" style="font-size: 3rem; color: var(--primary); margin-bottom: 1.5rem;"></i>
                <h3>Menghubungkan ke Layanan...</h3>
            </div>

            <div id="wa-off-state" style="display: none;">
                <div style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); color: var(--danger); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem;">
                    <i class="fas fa-power-off"></i>
                </div>
                <h2 style="margin-bottom: 0.5rem;">Layanan Mati</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Silakan aktifkan layanan untuk mulai mengirim notifikasi.</p>
                <button type="button" id="btn-wa-start" class="btn btn-primary btn-lg" style="width: 100%;">
                    <i class="fas fa-play"></i> Aktifkan Sekarang
                </button>
            </div>

            <div id="wa-qr-state" style="display: none;">
                <h3 style="margin-bottom: 1rem;">Scan QR Code</h3>
                <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.9rem;">Gunakan WhatsApp di HP Anda untuk menautkan perangkat.</p>
                <div id="wa-qr-code" style="background: #fff; padding: 15px; display: inline-block; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-bottom: 2rem;"></div>
                <button type="button" id="btn-wa-stop-qr" class="btn btn-ghost btn-sm" style="width: 100%;">
                    <i class="fas fa-stop"></i> Batalkan
                </button>
            </div>

            <div id="wa-connected-state" style="display: none;">
                <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); color: var(--success); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; border: 2px solid var(--success);">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h2 style="color: var(--success); margin-bottom: 0.5rem;">Terhubung!</h2>
                <p style="color: var(--text-secondary); margin-bottom: 2rem;">Sistem siap mengirimkan notifikasi otomatis.</p>
                <button type="button" id="btn-wa-stop-connected" class="btn btn-danger btn-sm" style="width: 100%;">
                    <i class="fas fa-stop"></i> Putuskan Koneksi
                </button>
            </div>

            <div id="wa-install-section" style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.05); display: none;">
                <div id="install-default-ui">
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1rem;">Layanan belum terinstall sempurna di server ini.</p>
                    <button type="button" id="btn-wa-install" class="btn btn-ghost btn-sm" style="width: 100%;">
                        <i class="fas fa-download"></i> Install Sekarang
                    </button>
                </div>
                
                <div id="install-progress-ui" style="display: none;">
                    <p id="install-status-text" style="font-size: 0.8rem; color: var(--primary-light); margin-bottom: 0.75rem;">Sedang menginstall...</p>
                    <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; margin-bottom: 1rem;">
                        <div id="install-progress-bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--accent)); transition: width 0.5s ease;"></div>
                    </div>
                    <small style="color: var(--text-secondary); font-size: 0.7rem;">Mohon jangan tutup halaman ini.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h2>Riwayat Notifikasi (Terbaru)</h2>
    </div>
    <div class="card-body">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Nomor Tujuan</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->evaluasi->pegawai->nama }}</td>
                        <td>{{ $log->nomor_tujuan }}</td>
                        <td>
                            @if($log->status == 'sent')
                                <span class="badge badge-success">Terkirim</span>
                            @elseif($log->status == 'failed')
                                <span class="badge badge-danger">Gagal</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $log->created_at->diffForHumans() }}</td>
                        <td><small>{{ $log->keterangan ?: '-' }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="empty-state">Belum ada riwayat notifikasi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const states = {
        loading: document.getElementById('wa-loading-state'),
        off: document.getElementById('wa-off-state'),
        qr: document.getElementById('wa-qr-state'),
        connected: document.getElementById('wa-connected-state'),
        install: document.getElementById('wa-install-section')
    };
    
    const qrCodeDiv = document.getElementById('wa-qr-code');

    function showState(state) {
        Object.keys(states).forEach(key => {
            states[key].style.display = (key === state || (key === 'install' && state === 'off')) ? 'block' : 'none';
        });
    }

    function checkStatus() {
        fetch('{{ route('wa.status') }}')
            .then(res => res.json())
            .then(data => {
                if (data.running) {
                    if (data.connected) {
                        showState('connected');
                    } else {
                        showState('qr');
                        fetchQR();
                    }
                } else {
                    showState('off');
                }
            })
            .catch(() => showState('off'));
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

    function startService() {
        showState('loading');
        fetch('{{ route('wa.start') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => setTimeout(checkStatus, 3000));
    }

    function stopService() {
        showState('loading');
        fetch('{{ route('wa.stop') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => setTimeout(checkStatus, 1000));
    }

    document.getElementById('btn-wa-start').addEventListener('click', startService);
    document.getElementById('btn-wa-stop-qr').addEventListener('click', stopService);
    document.getElementById('btn-wa-stop-connected').addEventListener('click', stopService);
    
    document.getElementById('btn-wa-install').addEventListener('click', function() {
        const defaultUI = document.getElementById('install-default-ui');
        const progressUI = document.getElementById('install-progress-ui');
        const progressBar = document.getElementById('install-progress-bar');
        const statusText = document.getElementById('install-status-text');

        defaultUI.style.display = 'none';
        progressUI.style.display = 'block';

        let progress = 0;
        const interval = setInterval(() => {
            if (progress < 90) {
                progress += Math.random() * 5;
                if (progress > 90) progress = 90;
                progressBar.style.width = progress + '%';
                
                if (progress > 60) statusText.innerText = 'Menyiapkan mesin...';
                else if (progress > 30) statusText.innerText = 'Mengunduh paket...';
            }
        }, 1000);

        fetch('{{ route('wa.install') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(res => res.json()).then(data => {
            clearInterval(interval);
            progressBar.style.width = '100%';
            statusText.innerText = 'Instalasi Selesai!';
            statusText.style.color = 'var(--success)';
            
            setTimeout(() => {
                progressUI.style.display = 'none';
                defaultUI.style.display = 'block';
                checkStatus();
            }, 2000);
        });
    });

    setInterval(checkStatus, 5000);
    checkStatus();
</script>
@endsection
