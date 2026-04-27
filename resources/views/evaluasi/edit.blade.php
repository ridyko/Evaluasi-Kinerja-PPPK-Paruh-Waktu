@extends('layouts.app')
@section('title', 'Edit Evaluasi')

@section('content')
<div class="page-header">
    <div>
        <h1>Edit Evaluasi Kinerja</h1>
        <p>{{ $evaluasi->pegawai->nama }} — {{ $evaluasi->nama_bulan }} {{ $evaluasi->tahun }}</p>
    </div>
    <div class="actions">
        <a href="{{ route('evaluasi.index') }}" class="btn btn-ghost"><i class="fas fa-arrow-left"></i> Kembali</a>
        @if($evaluasi->status === 'draft')
        <form action="{{ route('evaluasi.finalize', $evaluasi) }}" method="POST" onsubmit="return confirm('Yakin ingin memfinalisasi evaluasi ini? Setelah final tidak dapat diedit lagi.')">
            @csrf
            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Finalisasi</button>
        </form>
        @endif
    </div>
</div>

{{-- Info Cards --}}
<div class="grid-2" style="margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-user" style="color: var(--primary-light); margin-right: 0.5rem;"></i>Pegawai yang Dinilai</h2></div>
        <div class="card-body">
            <table style="width: 100%; font-size: 0.85rem;">
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary); width: 120px;">Nama</td><td style="font-weight: 600;">{{ $evaluasi->pegawai->nama }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">NI PPPK</td><td>{{ $evaluasi->pegawai->ni_pppk }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Pangkat/Gol</td><td>{{ $evaluasi->pegawai->pangkat_gol ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Jabatan</td><td>{{ $evaluasi->pegawai->jabatan->nama_jabatan ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Unit Kerja</td><td>{{ $evaluasi->pegawai->unit_kerja }}</td></tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-user-tie" style="color: var(--accent); margin-right: 0.5rem;"></i>Pejabat Penilai Kinerja</h2></div>
        <div class="card-body">
            <table style="width: 100%; font-size: 0.85rem;">
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary); width: 120px;">Nama</td><td style="font-weight: 600;">{{ $evaluasi->pejabatPenilai->nama }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">NIP</td><td>{{ $evaluasi->pejabatPenilai->nip }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Pangkat/Gol</td><td>{{ $evaluasi->pejabatPenilai->pangkat_gol ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Jabatan</td><td>{{ $evaluasi->pejabatPenilai->jabatan ?? '-' }}</td></tr>
                <tr><td style="padding: 0.3rem 0; color: var(--text-secondary);">Unit Kerja</td><td>{{ $evaluasi->pejabatPenilai->unit_kerja }}</td></tr>
            </table>
        </div>
    </div>
</div>

<form action="{{ route('evaluasi.update', $evaluasi) }}" method="POST">
    @csrf @method('PUT')

    {{-- Hasil Kerja --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h2><i class="fas fa-chart-line" style="color: var(--success); margin-right: 0.5rem;"></i>Hasil Kerja</h2>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary);">Capaian Hasil Kerja Bulanan:</span>
                <span style="font-size: 1.1rem; font-weight: 800; color: var(--success); margin-left: 0.5rem;" id="totalCapaian">{{ number_format($evaluasi->capaian_hasil_kerja, 2) }}</span>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Indikator Kinerja Individu</th>
                        <th style="width: 130px;">Target Tahunan</th>
                        <th style="width: 110px;">Target Bulan</th>
                        <th style="width: 110px;">Realisasi</th>
                        <th style="width: 100px;">Capaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluasi->hasilKerja as $i => $hk)
                    <tr>
                        <td style="text-align: center;">{{ $i + 1 }}</td>
                        <td style="font-size: 0.8rem;">{{ $hk->indikatorKinerja->deskripsi }}</td>
                        <td style="text-align: center; font-size: 0.8rem;">{{ $hk->indikatorKinerja->target_tahunan }}</td>
                        <td>
                            <input type="number" name="hasil_kerja[{{ $hk->id }}][target_bulan]"
                                   class="form-control target-bulan" value="{{ $hk->target_bulan }}"
                                   min="0" step="1" data-row="{{ $i }}"
                                   style="padding: 0.4rem 0.6rem; font-size: 0.85rem; text-align: center;">
                        </td>
                        <td>
                            <input type="number" name="hasil_kerja[{{ $hk->id }}][realisasi]"
                                   class="form-control realisasi" value="{{ $hk->realisasi }}"
                                   min="0" step="0.01" data-row="{{ $i }}"
                                   style="padding: 0.4rem 0.6rem; font-size: 0.85rem; text-align: center;">
                        </td>
                        <td style="text-align: center;">
                            <span class="capaian-value" id="capaian-{{ $i }}" style="font-weight: 700; color: var(--success);">
                                {{ number_format($hk->capaian, 0) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aspek Perilaku --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
            <h2><i class="fas fa-star" style="color: var(--warning); margin-right: 0.5rem;"></i>Aspek Perilaku (BerAKHLAK)</h2>
            <div>
                <span style="font-size: 0.75rem; color: var(--text-secondary);">Capaian Perilaku Kerja:</span>
                <span style="font-size: 1.1rem; font-weight: 800; color: var(--warning); margin-left: 0.5rem;" id="totalPerilaku">{{ number_format($evaluasi->capaian_perilaku_kerja, 2) }}</span>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div style="padding: 1rem 1.5rem; background: rgba(245, 158, 11, 0.05); border-bottom: 1px solid rgba(99, 102, 241, 0.06);">
                <div style="display: flex; gap: 2rem; font-size: 0.75rem; color: var(--text-secondary);">
                    <span><strong style="color: #34d399;">Diatas Ekspektasi</strong> = 3</span>
                    <span><strong style="color: #fbbf24;">Sesuai Ekspektasi</strong> = 2</span>
                    <span><strong style="color: #f87171;">Dibawah Ekspektasi</strong> = 1</span>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Aspek Perilaku</th>
                        <th style="width: 220px;">Pengkategorian</th>
                        <th style="width: 80px;">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluasi->perilaku as $i => $pr)
                    <tr>
                        <td style="text-align: center;">{{ $i + 1 }}</td>
                        <td style="font-weight: 500;">{{ $pr->aspek_perilaku }}</td>
                        <td>
                            <select name="perilaku[{{ $pr->id }}][pengkategorian]" class="form-select perilaku-select" data-row="{{ $i }}"
                                    style="padding: 0.4rem 0.6rem; font-size: 0.85rem;">
                                <option value="Dibawah Ekspektasi" {{ $pr->pengkategorian === 'Dibawah Ekspektasi' ? 'selected' : '' }}>Dibawah Ekspektasi</option>
                                <option value="Sesuai Ekspektasi" {{ $pr->pengkategorian === 'Sesuai Ekspektasi' ? 'selected' : '' }}>Sesuai Ekspektasi</option>
                                <option value="Diatas Ekspektasi" {{ $pr->pengkategorian === 'Diatas Ekspektasi' ? 'selected' : '' }}>Diatas Ekspektasi</option>
                            </select>
                        </td>
                        <td style="text-align: center;">
                            <span class="perilaku-nilai" id="perilaku-nilai-{{ $i }}" style="font-weight: 800; font-size: 1.1rem;">{{ $pr->nilai }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tanggal Evaluasi --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div class="form-group" style="max-width: 300px; margin-bottom: 0;">
                <label class="form-label">Tanggal Evaluasi</label>
                <input type="date" name="tanggal_evaluasi" class="form-control"
                       value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi?->format('Y-m-d')) }}">
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 1rem;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Evaluasi</button>
        <a href="{{ route('evaluasi.show', $evaluasi) }}" class="btn btn-ghost"><i class="fas fa-eye"></i> Preview</a>
        <a href="{{ route('evaluasi.pdf', $evaluasi) }}" class="btn btn-ghost"><i class="fas fa-file-pdf" style="color: #ef4444;"></i> Export PDF</a>
    </div>
</form>

@endsection

@section('scripts')
<script>
    // Auto-calculate capaian when target_bulan or realisasi changes
    document.querySelectorAll('.target-bulan, .realisasi').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.dataset.row;
            const targetBulan = parseFloat(document.querySelector(`.target-bulan[data-row="${row}"]`).value) || 0;
            const realisasi = parseFloat(document.querySelector(`.realisasi[data-row="${row}"]`).value) || 0;

            let capaian = 0;
            if (targetBulan > 0) {
                capaian = (realisasi / targetBulan) * 100;
            }

            document.getElementById(`capaian-${row}`).textContent = Math.round(capaian);

            // Recalculate total
            recalculateTotal();
        });
    });

    // Auto-update perilaku nilai
    document.querySelectorAll('.perilaku-select').forEach(select => {
        select.addEventListener('change', function() {
            const row = this.dataset.row;
            const kategori = this.value;
            let nilai = 2;
            if (kategori === 'Diatas Ekspektasi') nilai = 3;
            else if (kategori === 'Dibawah Ekspektasi') nilai = 1;

            const nilaiEl = document.getElementById(`perilaku-nilai-${row}`);
            nilaiEl.textContent = nilai;

            // Color coding
            nilaiEl.style.color = nilai === 3 ? '#34d399' : (nilai === 2 ? '#fbbf24' : '#f87171');

            recalculatePerilaku();
        });
    });

    function recalculateTotal() {
        let total = 0, count = 0;
        document.querySelectorAll('[id^="capaian-"]').forEach(el => {
            total += parseFloat(el.textContent) || 0;
            count++;
        });
        const avg = count > 0 ? total / count : 0;
        document.getElementById('totalCapaian').textContent = avg.toFixed(2);
    }

    function recalculatePerilaku() {
        let total = 0, count = 0;
        document.querySelectorAll('.perilaku-nilai').forEach(el => {
            total += parseInt(el.textContent) || 0;
            count++;
        });
        const avg = count > 0 ? total / count : 0;
        document.getElementById('totalPerilaku').textContent = avg.toFixed(2);
    }
</script>
@endsection
