<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ArsipImportController extends Controller
{
    public function form()
    {
        return view('arsips.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:20480',
            'mode' => 'required|in:append,replace',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->toArray(null, true, true, true);

        if ($request->input('mode') === 'replace') {
            Arsip::query()->forceDelete();
        }

        $inserted = 0;
        $skipped  = 0;

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex < 7) continue;

            $nomorArsip = trim((string)($row['C'] ?? ''));
            if (! is_numeric($nomorArsip) || (int)$nomorArsip < 1000) {
                $skipped++;
                continue;
            }

            Arsip::updateOrCreate(
                ['nomor_arsip' => $nomorArsip],
                [
                    'no_urut'              => $row['A'] ? (int)$row['A'] : null,
                    'kode_klasifikasi'     => trim((string)($row['B'] ?? '')),
                    'nomor_berkas'         => trim((string)($row['D'] ?? '')),
                    'uraian'               => trim((string)($row['E'] ?? '')),
                    'kurun_waktu'          => trim((string)($row['F'] ?? '')),
                    'uraian_informasi'     => trim((string)($row['G'] ?? '')),
                    'jumlah_item'          => trim((string)($row['H'] ?? '')),
                    'tanggal_arsip'        => trim((string)($row['I'] ?? '')),
                    'tingkat_perkembangan' => trim((string)($row['J'] ?? '')),
                    'keterangan'           => trim((string)($row['K'] ?? '')),
                    'lokasi'               => trim((string)($row['L'] ?? '')),
                    'boks'                 => trim((string)($row['M'] ?? '')),
                    'created_by'           => auth()->id(),
                    'updated_by'           => auth()->id(),
                ]
            );

            $inserted++;
        }

        return redirect()->route('arsips.index')
            ->with('status', "Import berhasil: {$inserted} baris dimasukkan, {$skipped} dilewati.");
    }
}
