<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalasi E-Kinerja — SMKN 2 Jakarta</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --surface: #0f172a;
            --surface-2: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body {
            background: var(--surface);
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }

        .install-container {
            width: 100%;
            max-width: 500px;
            background: var(--surface-2);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .header { text-align: center; margin-bottom: 2.5rem; }
        .logo {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, var(--primary), #06b6d4);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.5rem; font-weight: 800; color: #fff;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }

        h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 0.5rem; }
        p { color: var(--text-secondary); font-size: 0.95rem; }

        .form-group { margin-bottom: 1.25rem; }
        label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); }
        
        .input-wrapper { position: relative; }
        .input-wrapper i {
            position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
            color: var(--text-secondary); font-size: 1rem;
        }

        input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            color: #fff; font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: rgba(15, 23, 42, 0.8);
        }

        .btn-install {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff; border: none; border-radius: 12px;
            padding: 1rem; font-size: 1rem; font-weight: 700;
            cursor: pointer; transition: all 0.3s ease;
            margin-top: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        }

        .btn-install:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .btn-install:disabled { opacity: 0.6; cursor: not-allowed; }

        .loader {
            width: 20px; height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="header">
            <div class="logo">EK</div>
            <h1>Installer E-Kinerja</h1>
            <p>Konfigurasi basis data sistem evaluasi kinerja</p>
        </div>

        <form id="installForm">
            <div class="form-group" style="background: rgba(99, 102, 241, 0.05); padding: 1rem; border-radius: 12px; border: 1px dashed rgba(99, 102, 241, 0.3); margin-bottom: 2rem;">
                <label style="color: var(--primary);">Installer Secret Key</label>
                <div class="input-wrapper">
                    <i class="fas fa-shield-alt"></i>
                    <input type="password" name="installer_key" placeholder="Masukkan kode rahasia instalasi" required>
                </div>
                <small style="color: var(--text-secondary); font-size: 0.75rem; display: block; margin-top: 0.5rem;">
                    Hubungi administrator untuk mendapatkan kode rahasia ini.
                </small>
            </div>

            <div class="form-group">
                <label>Database Host</label>
                <div class="input-wrapper">
                    <i class="fas fa-server"></i>
                    <input type="text" name="db_host" value="127.0.0.1" required>
                </div>
            </div>

            <div class="grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Database Port</label>
                    <div class="input-wrapper">
                        <i class="fas fa-plug"></i>
                        <input type="text" name="db_port" value="3306" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Database Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-database"></i>
                        <input type="text" name="db_database" value="evakin" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Username Database</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="db_username" value="root" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password Database</label>
                <div class="input-wrapper">
                    <i class="fas fa-key"></i>
                    <input type="password" name="db_password" placeholder="Kosongkan jika tidak ada">
                </div>
            </div>

            <button type="submit" class="btn-install" id="submitBtn">
                <span id="btnText">Mulai Instalasi</span>
                <div class="loader" id="btnLoader"></div>
            </button>
        </form>
    </div>

    <script>
        document.getElementById('installForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submitBtn');
            const loader = document.getElementById('btnLoader');
            const text = document.getElementById('btnText');
            const formData = new FormData(this);

            btn.disabled = true;
            loader.style.display = 'block';
            text.innerText = 'Menginstal...';

            try {
                const response = await fetch('{{ route("install.setup") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Aplikasi berhasil dikonfigurasi. Silakan login.',
                        confirmButtonColor: '#6366f1'
                    }).then(() => {
                        window.location.href = '/login';
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message,
                    confirmButtonColor: '#ef4444'
                });
                btn.disabled = false;
                loader.style.display = 'none';
                text.innerText = 'Coba Lagi';
            }
        });
    </script>
</body>
</html>
