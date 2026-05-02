# EVAKIN - Evaluasi Kinerja Daerah

EVAKIN adalah sistem manajemen evaluasi kinerja bulanan untuk pegawai PPPK Paruh Waktu yang modern, cepat, dan efisien. Dilengkapi dengan sistem notifikasi WhatsApp otomatis mandiri untuk memastikan setiap pegawai mendapatkan informasi hasil evaluasi secara real-time.

![EVAKIN Banner](https://raw.githubusercontent.com/ridyko/Evaluasi-Kinerja-PPPK-Paruh-Waktu/main/public/img/banner.png)

## 🚀 Fitur Utama

- **Dashboard Modern**: Visualisasi data kinerja yang bersih dan intuitif.
- **Manajemen Pegawai**: Pengelolaan data pegawai lengkap dengan struktur jabatan.
- **Evaluasi Bulanan**: Input nilai kinerja (Hasil Kerja & Perilaku) yang akurat.
- **Export Laporan**: Cetak hasil evaluasi ke format PDF dan Excel.
- **WhatsApp Gateway (Self-Hosted)**:
    - Notifikasi otomatis saat finalisasi evaluasi.
    - Manajemen layanan langsung dari dashboard (Start/Stop/Reset).
    - Tampilan QR Code interaktif untuk sinkronisasi perangkat.
    - Riwayat pengiriman notifikasi (Status Terkirim/Gagal).
    - Fitur Kirim Ulang (Resend) notifikasi.
- **White-Label Branding**: Ubah Nama Organisasi, Logo, dan Favicon langsung dari pengaturan.

## 🛠️ Persyaratan Sistem

- PHP >= 8.2
- MySQL / MariaDB
- Composer
- **Node.js >= 18** (Wajib untuk WhatsApp Gateway)
- NPM

## 📥 Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/ridyko/Evaluasi-Kinerja-PPPK-Paruh-Waktu.git
   cd Evaluasi-Kinerja-PPPK-Paruh-Waktu
   ```

2. **Install Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan pengaturan database di file `.env`.*

4. **Migrasi Database**
   ```bash
   php artisan migrate --seed
   ```

5. **Setup WhatsApp Gateway**
   - Buka menu **WhatsApp Gateway** di dashboard Admin.
   - Klik tombol **"Install Sekarang"** untuk mengunduh library yang dibutuhkan.
   - Klik **"Aktifkan Sekarang"** dan scan QR Code yang muncul.

## 📱 Panduan WhatsApp Gateway

Sistem notifikasi WhatsApp di EVAKIN berjalan secara mandiri menggunakan mesin **Baileys**. Anda memiliki kontrol penuh atas layanan ini:

### Cara Menghubungkan:
1. Masuk sebagai **Administrator**.
2. Pilih menu **WhatsApp Gateway** di sidebar.
3. Klik **Aktifkan Sekarang**.
4. Gunakan WhatsApp di ponsel Anda (Perangkat Tertaut > Tautkan Perangkat) untuk melakukan scan pada QR Code yang tampil.

### Fitur Manajemen:
- **Aktifkan/Matikan**: Menjalankan atau menghentikan proses background tanpa menghapus login.
- **Ganti Akun / Reset**: Menghapus sesi login saat ini jika Anda ingin menggunakan nomor WhatsApp lain.
- **Kirim Ulang**: Jika notifikasi gagal dikirim (karena koneksi internet atau gateway mati), Anda bisa mengklik tombol WhatsApp hijau di daftar evaluasi untuk mencoba kembali.

## 🤝 Kontribusi

Kontribusi selalu terbuka! Silakan lakukan pull request atau buka issue untuk saran perbaikan.

## 📄 Lisensi

Proyek ini berada di bawah lisensi **MIT**.

---
*Dibuat dengan ❤️ untuk kemajuan manajemen kinerja pegawai daerah.*
