<?php

namespace App\Console\Commands;

use App\Models\Arsip;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportArsipExcel extends Command
{
    protected $signature = 'arsip:import {file? : Path to the xlsx file} {--fresh : Truncate table before import}';
    protected $description = 'Import data arsip inaktif dari file Excel (.xlsx)';

    public function handle(): int
    {
        $filePath = $this->argument('file')
            ?? base_path('Input anak magang 2026.xlsx');

        if (! file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        if ($this->option('fresh')) {
            Arsip::query()->forceDelete();
            $this->warn('Tabel arsips dikosongkan (fresh import).');
        }

        $this->info("Membaca file: {$filePath}");

        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, true);

        $bar      = $this->output->createProgressBar();
        $inserted = 0;
        $skipped  = 0;

        // Carry-forward state for merged cells
        $lastKode   = null;
        $lastNoBerkas = null;

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex < 7) continue;

            $nomorArsip = trim((string)($row['C'] ?? ''));

            if (! is_numeric($nomorArsip) || (int)$nomorArsip < 1000) {
                $skipped++;
                continue;
            }

            // Resolve merged-cell carry-forward for Kode Klasifikasi (col B)
            $kode = $this->clean($row['B']);
            if ($kode !== null) {
                $lastKode = $kode;
            } else {
                $kode = $lastKode; // inherit from previous group
            }

            // Resolve merged-cell carry-forward for Nomor Berkas (col D)
            $noBerkas = $this->clean($row['D']);
            if ($noBerkas !== null) {
                $lastNoBerkas = $noBerkas;
            } else {
                $noBerkas = $lastNoBerkas;
            }

            $data = [
                'no_urut'              => $row['A'] ? (int)$row['A'] : null,
                'kode_klasifikasi'     => $kode,
                'nomor_arsip'          => $nomorArsip,
                'nomor_berkas'         => $noBerkas,
                'uraian'               => $this->clean($row['E']),
                'kurun_waktu'          => $this->clean($row['F']),
                'uraian_informasi'     => $this->clean($row['G']),
                'jumlah_item'          => $this->clean($row['H']),
                'tanggal_arsip'        => $this->clean($row['I']),
                'tingkat_perkembangan' => $this->clean($row['J']),
                'keterangan'           => $this->clean($row['K']),
                'lokasi'               => $this->clean($row['L']),
                'boks'                 => $this->clean($row['M']),
            ];

            Arsip::updateOrCreate(
                ['nomor_arsip' => $nomorArsip],
                $data
            );

            $inserted++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Import selesai: {$inserted} baris dimasukkan, {$skipped} baris dilewati.");

        return 0;
    }

    private function clean($value): ?string
    {
        if ($value === null || $value === '') return null;
        return trim((string) $value);
    }
}
