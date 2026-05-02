# 🚀 EVAKIN (Evaluasi Kinerja)
### Sistem Evaluasi Kinerja PPPK Paruh Waktu

![Banner EVAKIN](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

**EVAKIN** adalah platform manajemen kinerja modern yang dirancang khusus untuk mengelola, memantau, dan mengevaluasi kinerja Pegawai Pemerintah dengan Perjanjian Kerja (PPPK) Paruh Waktu di lingkungan organisasi Anda.

---

## ✨ Fitur Utama

- **🛡️ Manajemen Akses Berbasis Peran (RBAC)**: Mendukung peran Admin, Pejabat Penilai, dan Pegawai dengan hak akses yang tersegregasi.
- 🏢 **White-Label Ready**: Ubah nama instansi, slogan, dan logo tanpa menyentuh kode.
- ⚙️ **Dynamic Settings**: Panel pengaturan sistem untuk Administrator (Nama Aplikasi, Organisasi, Logo, Favicon, & Footer).
- 📊 **Dashboard Modern**: Visualisasi statistik kinerja yang interaktif dan informatif.
- **📊 Pemantauan Indikator Kinerja**: Indikator kinerja yang disesuaikan untuk berbagai jabatan (Administrasi, Laboran, Pustakawan).
- **📝 Evaluasi Komprehensif**:
  - Evaluasi Kinerja Bulanan.
  - Penilaian Hasil Kerja.
  - Penilaian Perilaku Kerja.
- **📥 Ekspor Laporan Profesional**: Menghasilkan laporan evaluasi dalam format **PDF** dan **Excel** yang siap cetak.
- **⚙️ Manajemen Akun Terintegrasi**: Pembuatan akun otomatis untuk pegawai dan fitur reset password mandiri oleh Admin.
- **🛠️ Installer Otomatis**: Antarmuka web untuk konfigurasi basis data dan setup awal tanpa perlu menyentuh terminal.

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi |
| :--- | :--- |
| **Framework Utama** | [Laravel 11](https://laravel.com) |
| **Bahasa Pemrograman** | PHP 8.2+ |
| **Basis Data** | MySQL / MariaDB |
| **Frontend Styling** | TailwindCSS v4 & Vanilla CSS (Modern Dark Mode) |
| **Laporan PDF** | DomPDF |
| **Laporan Excel** | Maatwebsite/Laravel-Excel |

---

### 🎨 Kustomisasi Branding (White-Label)
Anda dapat mengubah identitas aplikasi langsung melalui menu **Pengaturan Sistem** di akun Administrator:
- **Identitas**: Nama Aplikasi, Nama Organisasi, Slogan.
- **Visual**: Unggah Logo Utama & Favicon (Ikon Tab Browser).
- **Informasi**: Teks Footer / Hak Cipta.

Seluruh perubahan akan diterapkan secara *real-time* di seluruh halaman aplikasi dan laporan.

---

## 🛠️ Instalasi & Penggunaan

Aplikasi ini dilengkapi dengan **Web Installer** untuk memudahkan setup awal:

### 1. Persyaratan Sistem
- PHP >= 8.2
- MySQL / MariaDB
- Composer
- Web Server (Apache/Nginx)

### 2. Langkah Instalasi
1. Clone repository atau salin folder proyek ke direktori web server Anda (misal: `htdocs`).
2. Jalankan `composer install` di direktori proyek.
3. Pastikan folder `storage` dan `bootstrap/cache` memiliki izin tulis (writable).
4. Akses aplikasi melalui browser di URL: `http://localhost/evakin/public/install`.
5. Masukkan **Installer Key** (Silakan cek file `.env` atau hubungi pengembang).
6. Isi konfigurasi database Anda (Host, Port, Database Name, Username, Password).
7. Klik **"Mulai Instalasi"**. Sistem akan otomatis melakukan migrasi database dan seeding data awal.

---

## 🔑 Kredensial Default (Akses Awal)

Gunakan akun berikut setelah instalasi selesai untuk masuk ke sistem:

| Peran | Email | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@evakin.test` | `password` |
| **Pejabat Penilai** | `pejabat@evakin.test` | `password` |

> [!TIP]
> Untuk akun **Pegawai**, password default adalah **NI PPPK** masing-masing pegawai yang dapat dilihat di menu Manajemen Pegawai oleh Admin.

---

## 📂 Struktur Proyek

- `app/Models/`: Definisi entitas data (Pegawai, Evaluasi, Indikator, dll).
- `app/Http/Controllers/`: Logika bisnis utama untuk setiap modul.
- `resources/views/`: Template antarmuka pengguna menggunakan Blade.
- `routes/web.php`: Definisi jalur akses (routing) aplikasi.
- `database/seeders/`: Data awal untuk jabatan dan indikator kinerja.

---

## 📝 Lisensi

Aplikasi ini dikembangkan untuk kebutuhan internal organisasi. Seluruh hak cipta dilindungi.

---

<p align="center">
  Dibuat dengan ❤️ oleh Rio Widyatmoko
</p>
