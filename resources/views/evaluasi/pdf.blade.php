<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Evaluasi Kinerja {{ $evaluasi->pegawai->nama }} - {{ $evaluasi->nama_bulan }} {{ $evaluasi->tahun }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm 15mm 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .header h1 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 1px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 9.5pt;
            font-weight: bold;
            margin-bottom: 1px;
            text-transform: uppercase;
        }

        .header h3 {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        table {
            width: 100%;
        }

        .section-title {
            font-weight: bold;
            font-size: 9pt;
            margin-top: 6px;
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>EVALUASI KINERJA BULANAN PEGAWAI</h1>
        <h2>PEGAWAI PEMERINTAH DENGAN PERJANJIAN KERJA PARUH WAKTU</h2>
        <h3>BULAN {{ strtoupper($evaluasi->nama_bulan) }}</h3>
    </div>

    {{-- Identity Section - Side by Side --}}
    <table style="width: 100%; margin-bottom: 6px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 4px;">
                <table style="width: 100%;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; text-align: center; font-weight: bold; font-size: 8pt; padding: 3px 4px;">PEGAWAI YANG DINILAI</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; width: 18px; text-align: center; padding: 2px 4px; font-size: 8.5pt;">1</td>
                        <td style="border: 1px solid #000; width: 110px; padding: 2px 4px; font-size: 8pt;">NAMA</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pegawai->nama) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">2</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">NI PPPK</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ $evaluasi->pegawai->ni_pppk }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">3</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">PANGKAT/GOL. RUANG</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ $evaluasi->pegawai->pangkat_gol ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">4</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">JABATAN</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pegawai->jabatan->nama_jabatan ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">5</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">UNIT KERJA</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pegawai->unit_kerja) }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 4px;">
                <table style="width: 100%;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="3" style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; text-align: center; font-weight: bold; font-size: 8pt; padding: 3px 4px;">PEJABAT PENILAI KINERJA</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; width: 18px; text-align: center; padding: 2px 4px; font-size: 8.5pt;">1</td>
                        <td style="border: 1px solid #000; width: 110px; padding: 2px 4px; font-size: 8pt;">NAMA</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pejabatPenilai->nama) }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">2</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">NIP</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ $evaluasi->pejabatPenilai->nip }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">3</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">PANGKAT/ GOL.RUANG</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pejabatPenilai->pangkat_gol ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">4</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">JABATAN</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pejabatPenilai->jabatan ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt;">5</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8pt;">UNIT KERJA</td>
                        <td style="border: 1px solid #000; padding: 2px 4px; font-size: 8.5pt;">{{ strtoupper($evaluasi->pejabatPenilai->unit_kerja) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- HASIL KERJA --}}
    <div class="section-title">HASIL KERJA</div>
    <table style="margin-bottom: 3px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 20px;">No</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px;">Indikator Kinerja Individu</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 75px;">Target Tahunan</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 50px;">Target Bulan</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 50px;">Realisasi</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 50px;">Capaian</td>
        </tr>
        @foreach($evaluasi->hasilKerja as $i => $hk)
        <tr>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt; vertical-align: top;">{{ $i + 1 }}</td>
            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; vertical-align: top;">{{ $hk->indikatorKinerja->deskripsi }}</td>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 7.5pt; vertical-align: top;">{{ $hk->indikatorKinerja->target_tahunan }}</td>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt; vertical-align: top;">{{ $hk->target_bulan }}</td>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt; vertical-align: top;">{{ number_format($hk->realisasi, 0) }}</td>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt; vertical-align: top;">{{ number_format($hk->capaian, 0) }}</td>
        </tr>
        @endforeach
    </table>

    <table style="margin-bottom: 8px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: 1px solid #000; font-weight: bold; font-size: 8.5pt; padding: 3px 5px; background-color: #f0f0f0;">Capaian Hasil Kerja Bulanan</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; width: 50px; font-size: 9pt; background-color: #f0f0f0;">{{ number_format($evaluasi->capaian_hasil_kerja, 2, ',', '.') }}</td>
        </tr>
    </table>

    {{-- ASPEK PERILAKU --}}
    <div class="section-title">ASPEK PERILAKU (BerAKHLAK)</div>

    @php
        $perilakuDeskripsi = [
            'Berorientasi Pelayanan' => [
                'Memahami dan memenuhi kebutuhan masyarakat',
                'Ramah, cekatan, solutif, dan dapat diandalkan',
                'Melakukan perbaikan tiada henti',
            ],
            'Akuntabel' => [
                'Melaksanakan tugas dengan jujur, bertanggung jawab, cermat, disiplin, dan berintegritas tinggi',
                'Menggunakan kekayaan dan barang milik negara secara bertanggung jawab, efektif, dan efisien',
                'Tidak menyalahgunakan kewenangan jabatan',
            ],
            'Kompeten' => [
                'Meningkatkan kompetensi diri untuk menjawab tantangan yang selalu berubah',
                'Membantu orang lain belajar',
                'Melaksanakan tugas dengan kualitas terbaik',
            ],
            'Harmonis' => [
                'Menghargai setiap orang apapun latar belakangnya',
                'Suka menolong orang lain',
                'Membangun lingkungan kerja yang kondusif',
            ],
            'Loyal' => [
                'Menjaga nama baik sesama ASN, Pimpinan, Instansi, dan Negara',
                'Menjaga rahasia jabatan dan negara',
                'Rela berkorban untuk mencapai tujuan yang lebih besar',
            ],
            'Adaptif' => [
                'Cepat menyesuaikan diri menghadapi perubahan',
                'Terus berinovasi dan mengembangkan kreativitas',
                'Bertindak proaktif',
            ],
            'Kolaboratif' => [
                'Memberi kesempatan kepada berbagai pihak untuk berkontribusi',
                'Terbuka dalam bekerja sama untuk menghasilkan nilai tambah',
                'Menggerakkan pemanfaatan berbagai sumber daya untuk tujuan bersama',
            ],
        ];
    @endphp

    <table style="margin-bottom: 3px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 20px;">No</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px;">Aspek Perilaku</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 130px;">Pengkategorian</td>
            <td style="border: 1px solid #000; background-color: #1a3a6b; color: #ffffff; font-weight: bold; text-align: center; font-size: 8pt; padding: 3px 4px; width: 35px;">Nilai</td>
        </tr>
        @foreach($evaluasi->perilaku as $i => $pr)
        <tr>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 8.5pt; vertical-align: top;">{{ $i + 1 }}</td>
            <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7.5pt; vertical-align: top;">
                <strong>{{ $pr->aspek_perilaku }}</strong>
                @if(isset($perilakuDeskripsi[$pr->aspek_perilaku]))
                    <br>
                    @foreach($perilakuDeskripsi[$pr->aspek_perilaku] as $desc)
                        <span style="font-size: 7pt;">&bull; {{ $desc }}</span><br>
                    @endforeach
                @endif
            </td>
            <td style="border: 1px solid #000; text-align: center; padding: 2px 4px; font-size: 7.5pt; vertical-align: middle;">{{ $pr->pengkategorian }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; padding: 2px 4px; font-size: 9pt; vertical-align: middle;">{{ $pr->nilai }}</td>
        </tr>
        @endforeach
    </table>

    <table style="margin-bottom: 10px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="border: 1px solid #000; font-weight: bold; font-size: 8.5pt; padding: 3px 5px; background-color: #f0f0f0;">Capaian Perilaku Kerja Bulanan</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold; width: 35px; font-size: 9pt; background-color: #f0f0f0;">{{ number_format($evaluasi->capaian_perilaku_kerja, 2, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Signature --}}
    @php
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        if ($evaluasi->tanggal_evaluasi) {
            $tanggal = 'Jakarta, ' . $evaluasi->tanggal_evaluasi->format('d') . ' ' . $bulanNames[(int)$evaluasi->tanggal_evaluasi->format('n')] . ' ' . $evaluasi->tanggal_evaluasi->format('Y');
        } else {
            $tanggal = 'Jakarta, __ __________ ' . $evaluasi->tahun;
        }
    @endphp

    <table style="width: 100%;" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="2" style="text-align: right; padding-bottom: 5px; font-size: 8.5pt;">
                {{ $tanggal }}
            </td>
        </tr>
        <tr>
            <td style="width: 50%; text-align: center; font-size: 8.5pt;">
                <p>Pegawai yang Dinilai,</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">{{ $evaluasi->pegawai->nama }}</p>
                <p>NI PPPK {{ $evaluasi->pegawai->ni_pppk }}</p>
            </td>
            <td style="width: 50%; text-align: center; font-size: 8.5pt;">
                <p>Pejabat Penilai Kinerja,</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">{{ $evaluasi->pejabatPenilai->nama }}</p>
                <p>NIP {{ $evaluasi->pejabatPenilai->nip }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
