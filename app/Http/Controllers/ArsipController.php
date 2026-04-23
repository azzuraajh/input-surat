<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $query = Arsip::query();

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_arsip', 'like', "%{$q}%")
                    ->orWhere('uraian', 'like', "%{$q}%")
                    ->orWhere('uraian_informasi', 'like', "%{$q}%")
                    ->orWhere('nomor_berkas', 'like', "%{$q}%");
            });
        }
        if ($request->filled('kode')) {
            $query->where('kode_klasifikasi', $request->string('kode')->toString());
        }
        if ($request->filled('uraian')) {
            $query->where('uraian', $request->string('uraian')->toString());
        }
        if ($request->filled('tahun')) {
            $query->where('kurun_waktu', $request->string('tahun')->toString());
        }
        if ($request->filled('boks')) {
            $query->where('boks', $request->string('boks')->toString());
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->string('lokasi')->toString() . '%');
        }

        $allowedSorts = ['nomor_arsip', 'kode_klasifikasi', 'uraian', 'kurun_waktu', 'tanggal_arsip', 'boks', 'created_at'];
        $sort = in_array($request->string('sort')->toString(), $allowedSorts, true)
            ? $request->string('sort')->toString()
            : 'nomor_arsip';
        $direction = $request->string('direction')->toString() === 'asc' ? 'asc' : 'desc';

        $arsips = $query->orderBy($sort, $direction)->paginate(50)->withQueryString();

        $filters = [
            'q'      => $request->string('q')->toString(),
            'kode'   => $request->string('kode')->toString(),
            'uraian' => $request->string('uraian')->toString(),
            'tahun'  => $request->string('tahun')->toString(),
            'boks'   => $request->string('boks')->toString(),
            'lokasi' => $request->string('lokasi')->toString(),
            'sort'   => $sort,
            'direction' => $direction,
        ];

        return view('arsips.index', [
            'arsips'    => $arsips,
            'filters'   => $filters,
            'kodeList'  => Arsip::kodeList(),
            'uraianList' => Arsip::uraianList(),
            'tahunList' => Arsip::tahunList(),
        ]);
    }

    public function create()
    {
        return view('arsips.create', [
            'arsip'     => new Arsip(),
            'kodeList'  => Arsip::kodeList(),
            'uraianList' => Arsip::uraianList(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_klasifikasi'     => 'nullable|string|max:50',
            'nomor_arsip'          => 'required|string|max:50|unique:arsips,nomor_arsip',
            'nomor_berkas'         => 'nullable|string|max:100',
            'uraian'               => 'nullable|string|max:200',
            'kurun_waktu'          => 'nullable|string|max:50',
            'uraian_informasi'     => 'nullable|string',
            'jumlah_item'          => 'nullable|string|max:100',
            'tanggal_arsip'        => 'nullable|string|max:100',
            'tingkat_perkembangan' => 'nullable|string|max:100',
            'keterangan'           => 'nullable|string|max:100',
            'lokasi'               => 'nullable|string|max:200',
            'boks'                 => 'nullable|string|max:50',
        ]);

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $arsip = Arsip::create($data);

        return redirect()->route('arsips.show', $arsip)
            ->with('status', 'Arsip berhasil ditambahkan.');
    }

    public function show(Arsip $arsip)
    {
        return view('arsips.show', compact('arsip'));
    }

    public function edit(Arsip $arsip)
    {
        return view('arsips.edit', [
            'arsip'     => $arsip,
            'kodeList'  => Arsip::kodeList(),
            'uraianList' => Arsip::uraianList(),
        ]);
    }

    public function update(Request $request, Arsip $arsip)
    {
        $data = $request->validate([
            'kode_klasifikasi'     => 'nullable|string|max:50',
            'nomor_arsip'          => 'required|string|max:50|unique:arsips,nomor_arsip,' . $arsip->id,
            'nomor_berkas'         => 'nullable|string|max:100',
            'uraian'               => 'nullable|string|max:200',
            'kurun_waktu'          => 'nullable|string|max:50',
            'uraian_informasi'     => 'nullable|string',
            'jumlah_item'          => 'nullable|string|max:100',
            'tanggal_arsip'        => 'nullable|string|max:100',
            'tingkat_perkembangan' => 'nullable|string|max:100',
            'keterangan'           => 'nullable|string|max:100',
            'lokasi'               => 'nullable|string|max:200',
            'boks'                 => 'nullable|string|max:50',
        ]);

        $data['updated_by'] = auth()->id();
        $arsip->update($data);

        return redirect()->route('arsips.show', $arsip)
            ->with('status', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        $arsip->delete();
        return redirect()->route('arsips.index')
            ->with('status', 'Arsip berhasil dihapus.');
    }

    public function exportXlsx(Request $request)
    {
        $arsips = $this->buildFilteredQuery($request)->get();
        return $this->buildSpreadsheetResponse($arsips, 'xlsx');
    }

    public function exportCsv(Request $request)
    {
        $arsips = $this->buildFilteredQuery($request)->get();
        return $this->buildSpreadsheetResponse($arsips, 'csv');
    }

    // ─────────────────────────────────────────────────────
    private function buildFilteredQuery(Request $request)
    {
        $query = Arsip::query();
        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($sub) use ($q) {
                $sub->where('nomor_arsip', 'like', "%{$q}%")
                    ->orWhere('uraian', 'like', "%{$q}%")
                    ->orWhere('uraian_informasi', 'like', "%{$q}%");
            });
        }
        if ($request->filled('kode'))   $query->where('kode_klasifikasi', $request->string('kode')->toString());
        if ($request->filled('uraian')) $query->where('uraian', $request->string('uraian')->toString());
        if ($request->filled('tahun'))  $query->where('kurun_waktu', $request->string('tahun')->toString());
        if ($request->filled('boks'))   $query->where('boks', $request->string('boks')->toString());
        return $query->orderBy('nomor_arsip', 'asc');
    }

    private function buildSpreadsheetResponse($arsips, string $format)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Arsip Inaktif KSDAE');

        // Title rows
        $sheet->setCellValue('A1', 'DAFTAR ARSIP INAKTIF Setditjen KSDAE');
        $sheet->setCellValue('A2', 'Unit Pengolah: Setditjen KSDAE');

        // Header row
        $headers = [
            'A' => 'No. Urut',
            'B' => 'Kode Klasifikasi',
            'C' => 'Nomor Arsip',
            'D' => 'Nomor Berkas',
            'E' => 'Uraian',
            'F' => 'Kurun Waktu',
            'G' => 'Uraian Informasi Arsip',
            'H' => 'Jumlah Item',
            'I' => 'Tanggal',
            'J' => 'Tk. Perkembangan',
            'K' => 'Keterangan (KKAA)',
            'L' => 'Lokasi',
            'M' => 'Boks',
        ];

        $row = 4;
        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}{$row}", $label);
        }

        // Style header
        $sheet->getStyle("A{$row}:M{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e293b']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Data rows
        $row = 5;
        $no = 1;
        foreach ($arsips as $arsip) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $arsip->kode_klasifikasi);
            $sheet->setCellValue("C{$row}", $arsip->nomor_arsip);
            $sheet->setCellValue("D{$row}", $arsip->nomor_berkas);
            $sheet->setCellValue("E{$row}", $arsip->uraian);
            $sheet->setCellValue("F{$row}", $arsip->kurun_waktu);
            $sheet->setCellValue("G{$row}", $arsip->uraian_informasi);
            $sheet->setCellValue("H{$row}", $arsip->jumlah_item);
            $sheet->setCellValue("I{$row}", $arsip->tanggal_arsip);
            $sheet->setCellValue("J{$row}", $arsip->tingkat_perkembangan);
            $sheet->setCellValue("K{$row}", $arsip->keterangan);
            $sheet->setCellValue("L{$row}", $arsip->lokasi);
            $sheet->setCellValue("M{$row}", $arsip->boks);
            $row++;
        }

        // Auto width
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('G')->setWidth(80);

        $filename = 'arsip-inaktif-ksdae-' . now()->format('Ymd-His');

        if ($format === 'csv') {
            $writer = new Csv($spreadsheet);
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, "{$filename}.csv", ['Content-Type' => 'text/csv']);
        }

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, "{$filename}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
