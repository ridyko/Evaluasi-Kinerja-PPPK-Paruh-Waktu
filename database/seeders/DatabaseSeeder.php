<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\PejabatPenilai;
use App\Models\IndikatorKinerja;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. JABATAN (Positions)
        // ==========================================
        $administrasi = Jabatan::create([
            'nama_jabatan' => 'Operator Layanan Operasional - Tenaga Administrasi',
            'deskripsi' => 'Tenaga administrasi pada satuan pendidikan',
        ]);

        $laboran = Jabatan::create([
            'nama_jabatan' => 'Operator Layanan Operasional - Tenaga Kependidikan - Laboran',
            'deskripsi' => 'Tenaga laboran pada satuan pendidikan',
        ]);

        $pustakawan = Jabatan::create([
            'nama_jabatan' => 'Operator Layanan Operasional - Pustakawan',
            'deskripsi' => 'Tenaga pustakawan pada satuan pendidikan',
        ]);

        // ==========================================
        // 2. INDIKATOR KINERJA per Jabatan
        // ==========================================

        // -- Administrasi --
        $indikatorAdm = [
            'Jumlah rekapitulasi pelaksanaan tugas administrasi pada satuan pendidikan meliputi urusan kepegawaian, kesiswaan, guru, kurikulum, prasarana dan sarana, aset dan keuangan',
            'Jumlah rekapitulasi pengolahan dan pemuktahiran dan penyajian data satuan pendidikan yang disusun secara manual maupun melalui sistem informasi yang berlaku pada satuan pendidikan',
            'Jumlah rekapitulasi perbantuan pelaksanaan kegiatan yang diselenggarakan satuan pendidikan',
            'Jumlah rekapitulasi persuratan meliputi konsep, penomoran, stempel, pendistribusian, penggandaan, legalisasi dan pengarsipan dokumen satuan pendidikan',
            'Jumlah rekapitulasi pelayanan masyarakat dan peserta didik yang membutuhkan layanan administrasi pada satuan pendidikan',
        ];

        foreach ($indikatorAdm as $i => $desc) {
            IndikatorKinerja::create([
                'jabatan_id' => $administrasi->id,
                'nomor_urut' => $i + 1,
                'deskripsi' => $desc,
                'target_tahunan' => '12 Rekapitulasi',
            ]);
        }

        // -- Laboran --
        $indikatorLab = [
            'Jumlah rekapitulasi penyiapan dan pengelolaan bahan serta alat praktik laboratorium pada satuan pendidikan',
            'Jumlah rekapitulasi perawatan dan pemeliharaan alat-alat laboratorium pada satuan pendidikan',
            'Jumlah rekapitulasi perbantuan pelaksanaan kegiatan praktikum peserta didik pada satuan pendidikan',
            'Jumlah rekapitulasi inventarisasi bahan dan alat laboratorium pada satuan pendidikan',
            'Jumlah rekapitulasi penerapan keselamatan dan kesehatan kerja (K3) di laboratorium pada satuan pendidikan',
        ];

        foreach ($indikatorLab as $i => $desc) {
            IndikatorKinerja::create([
                'jabatan_id' => $laboran->id,
                'nomor_urut' => $i + 1,
                'deskripsi' => $desc,
                'target_tahunan' => '12 Rekapitulasi',
            ]);
        }

        // -- Pustakawan --
        $indikatorPust = [
            'Jumlah rekapitulasi pengelolaan koleksi bahan pustaka perpustakaan pada satuan pendidikan',
            'Jumlah rekapitulasi pelayanan sirkulasi dan referensi perpustakaan pada satuan pendidikan',
            'Jumlah rekapitulasi perawatan dan pemeliharaan bahan pustaka perpustakaan pada satuan pendidikan',
            'Jumlah rekapitulasi promosi dan pembinaan minat baca peserta didik pada satuan pendidikan',
            'Jumlah rekapitulasi inventarisasi dan katalogisasi bahan pustaka perpustakaan pada satuan pendidikan',
        ];

        foreach ($indikatorPust as $i => $desc) {
            IndikatorKinerja::create([
                'jabatan_id' => $pustakawan->id,
                'nomor_urut' => $i + 1,
                'deskripsi' => $desc,
                'target_tahunan' => '12 Rekapitulasi',
            ]);
        }

        // ==========================================
        // 3. PEJABAT PENILAI
        // ==========================================
        $penilai = PejabatPenilai::create([
            'nama' => 'Mohamad Sodeli',
            'nip' => '197103051998021002',
            'pangkat_gol' => 'Pembina Tk.I (IV/B)',
            'jabatan' => 'Kepala Sub Bagian Tata Usaha',
            'unit_kerja' => 'SMK Negeri 2 Jakarta',
        ]);

        // ==========================================
        // 4. PEGAWAI (7 orang) + AUTO-CREATE USER ACCOUNTS
        // ==========================================
        $pegawaiData = [
            [
                'nama' => 'Bambang Hermawan',
                'ni_pppk' => '198010062025211090',
                'pangkat_gol' => 'V',
                'jabatan_id' => $administrasi->id,
                'email' => 'abeng.beken08@gmail.com',
            ],
            [
                'nama' => 'Euis Mufida',
                'ni_pppk' => '198207132025212054',
                'pangkat_gol' => 'V',
                'jabatan_id' => $pustakawan->id,
                'email' => 'euis.mufi@yahoo.com',
            ],
            [
                'nama' => 'Frans Hanip Akbar',
                'ni_pppk' => '198406102025211186',
                'pangkat_gol' => 'V',
                'jabatan_id' => $administrasi->id,
                'email' => 'franzelnino3@gmail.com',
            ],
            [
                'nama' => 'Galuh Desy Rahmawati',
                'ni_pppk' => '199312092025212138',
                'pangkat_gol' => 'V',
                'jabatan_id' => $administrasi->id,
                'email' => 'galuh_ak1@yahoo.co.id',
            ],
            [
                'nama' => 'Nur Umami',
                'ni_pppk' => '198807192025212131',
                'pangkat_gol' => 'V',
                'jabatan_id' => $administrasi->id,
                'email' => 'nurkayana17@gmail.com',
            ],
            [
                'nama' => 'Rio Widyatmoko',
                'ni_pppk' => '199206252025211130',
                'pangkat_gol' => 'V',
                'jabatan_id' => $laboran->id,
                'email' => 'riowidyatmoko@gmail.com',
            ],
            [
                'nama' => 'Sapto Prasetyo',
                'ni_pppk' => '199109282025211106',
                'pangkat_gol' => 'V',
                'jabatan_id' => $administrasi->id,
                'email' => 'prasetyosapto30@yahoo.co.id',
            ],
        ];

        foreach ($pegawaiData as $data) {
            $pegawai = Pegawai::create([
                'nama' => $data['nama'],
                'ni_pppk' => $data['ni_pppk'],
                'pangkat_gol' => $data['pangkat_gol'],
                'jabatan_id' => $data['jabatan_id'],
                'unit_kerja' => 'SMK Negeri 2 Jakarta',
            ]);

            // Auto-create user account (password = NI PPPK)
            User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['ni_pppk']),
                'role' => 'pegawai',
                'pegawai_id' => $pegawai->id,
            ]);
        }

        // ==========================================
        // 5. SPECIAL USERS (Admin & Penilai)
        // ==========================================

        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@evakin.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Penilai
        User::create([
            'name' => 'Mohamad Sodeli',
            'email' => 'pejabat@evakin.test',
            'password' => Hash::make('password'),
            'role' => 'penilai',
            'pejabat_penilai_id' => $penilai->id,
        ]);
    }
}
