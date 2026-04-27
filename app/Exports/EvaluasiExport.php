<?php

namespace App\Exports;

use App\Models\EvaluasiBulanan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class EvaluasiExport
{
    protected EvaluasiBulanan $evaluasi;
    protected Spreadsheet $spreadsheet;
    protected Worksheet $sheet;

    const COLOR_LIGHT_BLUE = 'BDD7EE'; 
    const COLOR_WHITE = 'FFFFFF';
    const COLOR_BLACK = '000000';

    public function __construct(EvaluasiBulanan $evaluasi)
    {
        $this->evaluasi = $evaluasi;
    }

    public function generate(): string
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->sheet->setTitle('Evaluasi Kinerja');

        // Page setup
        $this->sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $this->sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $this->sheet->getPageSetup()->setFitToWidth(1);
        $this->sheet->getPageSetup()->setFitToHeight(0);
        
        $this->sheet->getPageMargins()->setTop(0.4);
        $this->sheet->getPageMargins()->setBottom(0.4);
        $this->sheet->getPageMargins()->setLeft(0.4);
        $this->sheet->getPageMargins()->setRight(0.4);

        // Default font
        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);

        // Define Column Widths for a clean grid
        $this->sheet->getColumnDimension('A')->setWidth(4);   // No
        $this->sheet->getColumnDimension('B')->setWidth(15);  // Key Label 1
        $this->sheet->getColumnDimension('C')->setWidth(25);  // Value 1
        $this->sheet->getColumnDimension('D')->setWidth(4);   // No Right
        $this->sheet->getColumnDimension('E')->setWidth(15);  // Key Label 2
        $this->sheet->getColumnDimension('F')->setWidth(15);  // Value 2
        $this->sheet->getColumnDimension('G')->setWidth(12);  // Extra col for table headers

        $row = 1;
        $row = $this->writeHeader($row);
        $row = $this->writeIdentity($row);
        $row = $this->writeHasilKerja($row);
        $row = $this->writePerilaku($row);
        $this->writeSignature($row);

        // Save to temp file
        $filename = 'Evaluasi_Kinerja_' . str_replace(' ', '_', $this->evaluasi->pegawai->nama)
            . '_' . $this->evaluasi->nama_bulan . '_' . $this->evaluasi->tahun . '.xlsx';
        $path = storage_path('app/' . $filename);
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($path);

        return $path;
    }

    public function getFilename(): string
    {
        return 'Evaluasi_Kinerja_' . str_replace(' ', '_', $this->evaluasi->pegawai->nama)
            . '_' . $this->evaluasi->nama_bulan . '_' . $this->evaluasi->tahun . '.xlsx';
    }

    protected function writeHeader(int $row): int
    {
        $range = "A{$row}:G{$row}";
        $this->sheet->mergeCells($range);
        $this->sheet->setCellValue("A{$row}", 'EVALUASI KINERJA BULANAN PEGAWAI');
        $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['bold' => true]]);
        $row++;

        $this->sheet->mergeCells("A{$row}:G{$row}");
        $this->sheet->setCellValue("A{$row}", 'PEGAWAI PEMERINTAH DENGAN PERJANJIAN KERJA PARUH WAKTU');
        $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['bold' => true]]);
        $row++;

        $this->sheet->mergeCells("A{$row}:G{$row}");
        $this->sheet->setCellValue("A{$row}", 'BULAN ' . strtoupper($this->evaluasi->nama_bulan));
        $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['bold' => true]]);
        $row += 2;

        return $row;
    }

    protected function writeIdentity(int $row): int
    {
        $pegawai = $this->evaluasi->pegawai;
        $penilai = $this->evaluasi->pejabatPenilai;

        // Headers
        $this->sheet->setCellValue("A{$row}", 'NO');
        $this->sheet->mergeCells("B{$row}:C{$row}");
        $this->sheet->setCellValue("B{$row}", 'PEGAWAI YANG DINILAI');
        
        $this->sheet->setCellValue("D{$row}", 'NO');
        $this->sheet->mergeCells("E{$row}:G{$row}");
        $this->sheet->setCellValue("E{$row}", 'PEJABAT PENILAI KINERJA');

        $this->applyHeaderStyle("A{$row}:C{$row}");
        $this->applyHeaderStyle("D{$row}:G{$row}");
        $row++;

        $leftData = [
            ['1', 'NAMA', strtoupper($pegawai->nama)],
            ['2', 'NI PPPK', $pegawai->ni_pppk],
            ['3', 'PANGKAT/GOL. RUANG', $pegawai->pangkat_gol ?? '-'],
            ['4', 'JABATAN', $pegawai->jabatan->nama_jabatan ?? '-'],
            ['5', 'UNIT KERJA', strtoupper($pegawai->unit_kerja)],
        ];

        $rightData = [
            ['1', 'NAMA', strtoupper($penilai->nama)],
            ['2', 'NIP', $penilai->nip],
            ['3', 'PANGKAT/ GOL.RUANG', strtoupper($penilai->pangkat_gol ?? '-')],
            ['4', 'JABATAN', strtoupper($penilai->jabatan ?? '-')],
            ['5', 'UNIT KERJA', strtoupper($penilai->unit_kerja)],
        ];

        for ($i = 0; $i < 5; $i++) {
            $r = $row + $i;
            $this->sheet->setCellValue("A{$r}", $leftData[$i][0]);
            $this->sheet->setCellValue("B{$r}", $leftData[$i][1]);
            $this->sheet->setCellValue("C{$r}", $leftData[$i][2]);
            $this->applyBorder("A{$r}:C{$r}");
            $this->applyStyle("A{$r}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);

            $this->sheet->setCellValue("D{$r}", $rightData[$i][0]);
            $this->sheet->setCellValue("E{$r}", $rightData[$i][1]);
            $this->sheet->mergeCells("F{$r}:G{$r}");
            $this->sheet->setCellValue("F{$r}", $rightData[$i][2]);
            $this->applyBorder("D{$r}:G{$r}");
            $this->applyStyle("D{$r}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        }

        $row += 6;
        return $row;
    }

    protected function writeHasilKerja(int $row): int
    {
        $this->sheet->setCellValue("A{$row}", 'HASIL KERJA');
        $this->sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        // Table Header
        $this->sheet->setCellValue("A{$row}", 'No');
        $this->sheet->mergeCells("B{$row}:C{$row}");
        $this->sheet->setCellValue("B{$row}", 'Indikator Kinerja Individu');
        $this->sheet->setCellValue("D{$row}", 'Target tahunan');
        $this->sheet->setCellValue("E{$row}", 'Target Bulan');
        $this->sheet->setCellValue("F{$row}", 'Realisasi');
        $this->sheet->setCellValue("G{$row}", 'Capaian');

        $this->applyBorder("A{$row}:G{$row}");
        $this->applyStyle("A{$row}:G{$row}", [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'font' => ['bold' => true, 'size' => 8]
        ]);
        $row++;

        foreach ($this->evaluasi->hasilKerja as $i => $hk) {
            $this->sheet->setCellValue("A{$row}", $i + 1);
            $this->sheet->mergeCells("B{$row}:C{$row}");
            $this->sheet->setCellValue("B{$row}", $hk->indikatorKinerja->deskripsi);
            
            // Fix double Rekapitulasi - only use what's in database
            $targetStr = $hk->indikatorKinerja->target_tahunan;
            $this->sheet->setCellValue("D{$row}", $targetStr);
            
            $this->sheet->setCellValue("E{$row}", $hk->target_bulan);
            $this->sheet->setCellValue("F{$row}", $hk->realisasi);
            $this->sheet->setCellValue("G{$row}", number_format($hk->capaian, 0));

            $this->applyBorder("A{$row}:G{$row}");
            $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_TOP]]);
            $this->applyStyle("B{$row}", ['alignment' => ['wrapText' => true, 'vertical' => Alignment::VERTICAL_TOP], 'font' => ['size' => 8]]);
            $this->applyStyle("D{$row}:G{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_TOP], 'font' => ['size' => 8]]);
            $row++;
        }

        // Total Row
        $this->sheet->mergeCells("A{$row}:F{$row}");
        $this->sheet->setCellValue("A{$row}", 'Capaian Hasil Kerja Bulanan');
        $this->sheet->setCellValue("G{$row}", number_format($this->evaluasi->capaian_hasil_kerja, 2, ',', '.'));
        $this->applyBorder("A{$row}:G{$row}");
        $this->applyStyle("A{$row}", ['font' => ['bold' => true]]);
        $this->applyStyle("G{$row}", ['font' => ['bold' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $row += 2;

        return $row;
    }

    protected function writePerilaku(int $row): int
    {
        $this->sheet->setCellValue("A{$row}", 'No');
        $this->sheet->mergeCells("B{$row}:E{$row}");
        $this->sheet->setCellValue("B{$row}", 'Aspek Perilaku');
        $this->sheet->mergeCells("F{$row}:G{$row}");
        $this->sheet->setCellValue("F{$row}", 'Nilai');

        $this->applyBorder("A{$row}:G{$row}");
        $this->applyStyle("A{$row}:G{$row}", [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'font' => ['bold' => true, 'size' => 8]
        ]);
        $row++;

        foreach ($this->evaluasi->perilaku as $i => $pr) {
            $this->sheet->setCellValue("A{$row}", $i + 1);
            $this->sheet->mergeCells("B{$row}:E{$row}");
            $this->sheet->setCellValue("B{$row}", $pr->aspek_perilaku);
            $this->sheet->setCellValue("F{$row}", $pr->pengkategorian);
            $this->sheet->setCellValue("G{$row}", $pr->nilai);

            $this->applyBorder("A{$row}:G{$row}");
            $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
            $this->applyStyle("B{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT], 'font' => ['size' => 8]]);
            $this->applyStyle("F{$row}:G{$row}", ['font' => ['size' => 8]]);
            $row++;
        }

        $this->sheet->mergeCells("A{$row}:F{$row}");
        $this->sheet->setCellValue("A{$row}", 'Capaian Perilaku Kerja Bulanan');
        $this->sheet->setCellValue("G{$row}", number_format($this->evaluasi->capaian_perilaku_kerja, 2, ',', '.'));
        $this->applyBorder("A{$row}:G{$row}");
        $this->applyStyle("A{$row}", ['font' => ['bold' => true]]);
        $this->applyStyle("G{$row}", ['font' => ['bold' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $row += 2;

        return $row;
    }

    protected function writeSignature(int $row): void
    {
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        if ($this->evaluasi->tanggal_evaluasi) {
            $tgl = $this->evaluasi->tanggal_evaluasi;
            $tanggalText = 'Jakarta, ' . $tgl->format('d') . ' ' . $bulanNames[(int)$tgl->format('n')] . ' ' . $tgl->format('Y');
        } else {
            $tanggalText = 'Jakarta, ' . date('d') . ' ' . $bulanNames[(int)date('n')] . ' ' . $this->evaluasi->tahun;
        }

        $this->sheet->mergeCells("E{$row}:G{$row}");
        $this->sheet->setCellValue("E{$row}", $tanggalText);
        $this->applyStyle("E{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT], 'font' => ['size' => 8]]);
        $row++;

        $this->sheet->mergeCells("A{$row}:C{$row}");
        $this->sheet->setCellValue("A{$row}", 'Pegawai yang Dinilai');
        $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['size' => 8]]);

        $this->sheet->mergeCells("E{$row}:G{$row}");
        $this->sheet->setCellValue("E{$row}", 'Pejabat Penilai Kinerja,');
        $this->applyStyle("E{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['size' => 8]]);
        $row += 4;

        $this->sheet->mergeCells("A{$row}:C{$row}");
        $this->sheet->setCellValue("A{$row}", $this->evaluasi->pegawai->nama);
        $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['bold' => true]]);

        $this->sheet->mergeCells("E{$row}:G{$row}");
        $this->sheet->setCellValue("E{$row}", $this->evaluasi->pejabatPenilai->nama);
        $this->applyStyle("E{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['bold' => true]]);
        $row++;

        $this->sheet->mergeCells("A{$row}:C{$row}");
        $this->sheet->setCellValue("A{$row}", 'NI PPPK ' . $this->evaluasi->pegawai->ni_pppk);
        $this->applyStyle("A{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['size' => 8]]);

        $this->sheet->mergeCells("E{$row}:G{$row}");
        $this->sheet->setCellValue("E{$row}", 'NIP ' . $this->evaluasi->pejabatPenilai->nip);
        $this->applyStyle("E{$row}", ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER], 'font' => ['size' => 8]]);
    }

    protected function applyHeaderStyle(string $range): void
    {
        $style = $this->sheet->getStyle($range);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::COLOR_LIGHT_BLUE);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $style->getFont()->setBold(true)->setSize(8);
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    protected function applyBorder(string $range): void
    {
        $this->sheet->getStyle($range)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
    }

    protected function applyStyle(string $cell, array $styles): void
    {
        $style = $this->sheet->getStyle($cell);
        if (isset($styles['font'])) {
            $font = $style->getFont();
            if (isset($styles['font']['bold'])) $font->setBold($styles['font']['bold']);
            if (isset($styles['font']['size'])) $font->setSize($styles['font']['size']);
        }
        if (isset($styles['alignment'])) {
            $alignment = $style->getAlignment();
            if (isset($styles['alignment']['horizontal'])) $alignment->setHorizontal($styles['alignment']['horizontal']);
            if (isset($styles['alignment']['vertical'])) $alignment->setVertical($styles['alignment']['vertical']);
            if (isset($styles['alignment']['wrapText'])) $alignment->setWrapText($styles['alignment']['wrapText']);
        }
    }
}
