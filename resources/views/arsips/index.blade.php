@extends('layouts.app')

@section('content')
@php
    $hasActiveFilters = filled($filters['q']) || filled($filters['kode']) || filled($filters['uraian']) || filled($filters['tahun']) || filled($filters['lokasi']) || filled($filters['boks']);
    $activeFilters = collect([
        'Kata kunci' => $filters['q'],
        'Kode' => $filters['kode'],
        'Jenis' => $filters['uraian'],
        'Tahun' => $filters['tahun'],
        'Lokasi' => $filters['lokasi'],
        'Boks' => $filters['boks'],
    ])->filter(fn ($value) => filled($value));
    $sortLabels = [
        'nomor_arsip' => 'Nomor Arsip',
        'kode_klasifikasi' => 'Kode Klasifikasi',
        'uraian' => 'Jenis Dokumen',
        'kurun_waktu' => 'Kurun Waktu',
        'tanggal_arsip' => 'Tanggal Arsip',
        'boks' => 'Boks',
        'created_at' => 'Waktu Input',
    ];
@endphp

<div class="fade-up space-y-6">
    <section class="page-shell rounded-[28px] p-6 sm:p-8">
        <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
            <div class="max-w-2xl">
                <span class="section-kicker">Database Arsip</span>
                <h2 class="display-title mt-2 text-3xl leading-tight text-[var(--color-ink)] sm:text-[2.7rem]">
                    Arsip inaktif Setditjen KSDAE
                </h2>
                <p class="mt-3 text-sm leading-7 text-[var(--color-muted)] sm:text-base">
                    Kelola rekaman arsip, pencarian, import Excel, dan pembaruan metadata dalam tampilan yang lebih ringkas dan mudah dibaca.
                </p>
            </div>

            <div class="flex flex-wrap gap-3 xl:max-w-[420px] xl:justify-end">
                <div class="group relative">
                    <button type="button" class="btn-secondary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                    <div class="invisible absolute right-0 top-full z-30 mt-2 min-w-[210px] rounded-[18px] border border-[rgba(229,231,235,1)] bg-white p-2 opacity-0 shadow-[0_12px_30px_rgba(15,23,42,0.08)] transition-all group-hover:visible group-hover:opacity-100">
                        <a href="{{ route('arsips.export.xlsx', request()->query()) }}" class="flex rounded-[12px] px-4 py-3 text-sm font-medium text-[var(--color-ink)] hover:bg-[rgba(248,250,252,1)]">
                            Format Excel (.xlsx)
                        </a>
                        <a href="{{ route('arsips.export.csv', request()->query()) }}" class="mt-1 flex rounded-[12px] px-4 py-3 text-sm font-medium text-[var(--color-ink)] hover:bg-[rgba(248,250,252,1)]">
                            Format CSV
                        </a>
                    </div>
                </div>

                <a href="{{ route('arsips.import.form') }}" class="btn-secondary">Import Excel</a>
                <a href="{{ route('arsips.create') }}" class="btn-primary">Tambah Arsip</a>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="metric-card">
                <span class="metric-label">Total data</span>
                <span class="metric-value">{{ number_format($arsips->total()) }}</span>
                <p class="text-sm leading-6 text-[var(--color-muted)]">Rekaman arsip yang tersedia di sistem.</p>
            </div>

            <div class="metric-card">
                <span class="metric-label">Halaman aktif</span>
                <span class="metric-value">{{ $arsips->currentPage() }}/{{ $arsips->lastPage() }}</span>
                <p class="text-sm leading-6 text-[var(--color-muted)]">Menampilkan {{ $arsips->count() }} entri pada halaman ini.</p>
            </div>

            <div class="metric-card">
                <span class="metric-label">Urutan data</span>
                <span class="metric-value text-[1.15rem] sm:text-[1.3rem]">{{ $sortLabels[$filters['sort']] ?? 'Nomor Arsip' }}</span>
                <p class="text-sm leading-6 text-[var(--color-muted)]">
                    {{ $filters['direction'] === 'asc' ? 'Menaik' : 'Menurun' }}
                    @if($hasActiveFilters)
                        dengan {{ $activeFilters->count() }} filter aktif.
                    @else
                        tanpa filter tambahan.
                    @endif
                </p>
            </div>
        </div>

        @if($activeFilters->isNotEmpty())
            <div class="mt-5 flex flex-wrap gap-2">
                @foreach($activeFilters as $label => $value)
                    <span class="status-pill">{{ $label }}: {{ $value }}</span>
                @endforeach
            </div>
        @endif
    </section>

    <section class="soft-panel rounded-[28px] p-5 sm:p-6">
        <div class="mb-5">
            <span class="section-kicker">Filter dan Pencarian</span>
            <h3 class="mt-2 text-xl font-semibold text-[var(--color-ink)]">Saring arsip</h3>
            <p class="mt-2 max-w-2xl text-sm leading-7 text-[var(--color-muted)]">
                Gunakan kombinasi kata kunci, jenis dokumen, tahun, lokasi, dan box untuk mempersempit hasil sesuai kebutuhan.
            </p>
        </div>

        <form method="GET" class="grid gap-4 lg:grid-cols-[1.4fr_repeat(5,minmax(0,1fr))] lg:items-end">
            <div class="lg:col-span-2">
                <label class="form-label">Pencarian cepat</label>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nomor arsip, nomor berkas, jenis, atau uraian informasi..." class="form-input">
            </div>

            <div>
                <label class="form-label">Kode</label>
                <select name="kode" class="form-select">
                    <option value="">Semua kode</option>
                    @foreach($kodeList as $opt)
                        <option value="{{ $opt }}" @selected($filters['kode'] === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Jenis</label>
                <select name="uraian" class="form-select">
                    <option value="">Semua jenis</option>
                    @foreach($uraianList as $opt)
                        <option value="{{ $opt }}" @selected($filters['uraian'] === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="">Semua tahun</option>
                    @foreach($tahunList as $opt)
                        <option value="{{ $opt }}" @selected($filters['tahun'] === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" value="{{ $filters['lokasi'] }}" placeholder="Rak atau ruangan" class="form-input">
            </div>

            <div>
                <label class="form-label">Boks</label>
                <input type="text" name="boks" value="{{ $filters['boks'] }}" placeholder="Nomor box" class="form-input">
            </div>

            <div class="flex flex-col gap-3 sm:flex-row lg:col-span-full lg:justify-end">
                @if($hasActiveFilters)
                    <a href="{{ route('arsips.index') }}" class="btn-ghost">Reset filter</a>
                @endif
                <button type="submit" class="btn-primary">Terapkan Filter</button>
            </div>
        </form>
    </section>

    <section class="page-shell overflow-hidden rounded-[32px]">
        <div class="flex flex-col gap-3 border-b border-[rgba(229,231,235,1)] px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <span class="section-kicker">Hasil Pencarian</span>
                <h3 class="mt-2 text-xl font-semibold text-[var(--color-ink)]">
                    Menampilkan {{ $arsips->count() }} entri pada halaman {{ $arsips->currentPage() }}
                </h3>
            </div>
            <p class="text-sm leading-6 text-[var(--color-muted)]">
                Total {{ number_format($arsips->total()) }} data tersimpan dan siap ditinjau.
            </p>
        </div>

        <div class="overflow-auto overflow-y-auto max-h-[68vh] table-scroll-container">
            <table class="data-table whitespace-nowrap text-left">
                <thead class="sticky top-0">
                    <tr>
                        <th class="table-frozen-start w-[72px] text-center">#</th>
                        @php
                            $sortableCols = [
                                'kode_klasifikasi' => 'Kode',
                                'nomor_arsip' => 'No. Arsip',
                            ];
                        @endphp
                        @foreach($sortableCols as $field => $label)
                            <th class="{{ $field === 'nomor_arsip' ? 'table-frozen-second w-[150px]' : 'w-[120px]' }}">
                                <a
                                    href="{{ route('arsips.index', array_merge(request()->query(), ['sort' => $field, 'direction' => ($filters['sort'] === $field && $filters['direction'] === 'asc') ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-2 transition-colors hover:text-[var(--color-ink)]"
                                >
                                    {{ $label }}
                                    @if($filters['sort'] === $field)
                                        <span class="text-[var(--color-accent-strong)]">{{ $filters['direction'] === 'asc' ? '↑' : '↓' }}</span>
                                    @else
                                        <span class="text-[rgba(155,133,116,0.7)]">↕</span>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="w-[150px]">No. Berkas</th>
                        <th class="w-[160px]">Jenis</th>
                        <th class="w-[110px]">Tahun</th>
                        <th class="min-w-[320px]">Uraian Informasi Arsip</th>
                        <th class="w-[130px]">Jumlah</th>
                        <th class="w-[150px]">Tanggal</th>
                        <th class="w-[170px]">Tk. Perkembangan</th>
                        <th class="w-[120px]">Status</th>
                        <th class="w-[170px]">Lokasi Rak</th>
                        <th class="w-[100px] text-center">Boks</th>
                        <th class="table-frozen-end w-[140px] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsips as $i => $arsip)
                        <tr class="group">
                            <td class="table-frozen-start text-center text-sm font-medium text-[var(--color-muted)]">
                                {{ $arsips->firstItem() + $i }}
                            </td>

                            <td>
                                <span class="inline-flex rounded-full bg-[rgba(245,239,232,1)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-[var(--color-accent-strong)]">
                                    {{ $arsip->kode_klasifikasi ?? '—' }}
                                </span>
                            </td>

                            <td class="table-frozen-second">
                                <a href="{{ route('arsips.show', $arsip) }}" class="font-mono text-sm font-semibold text-[var(--color-ink)] hover:text-[var(--color-accent-strong)] hover:underline">
                                    {{ $arsip->nomor_arsip }}
                                </a>
                            </td>

                            <td class="max-w-[150px] truncate text-sm text-[var(--color-muted)]" title="{{ $arsip->nomor_berkas }}">
                                {{ $arsip->nomor_berkas ?? '—' }}
                            </td>

                            <td>
                                <span class="inline-flex rounded-full border border-[rgba(229,231,235,1)] bg-white px-3 py-1 text-sm font-medium text-[var(--color-ink)]">
                                    {{ $arsip->uraian ?? '—' }}
                                </span>
                            </td>

                            <td class="font-mono text-sm text-[var(--color-muted)]">{{ $arsip->kurun_waktu ?? '—' }}</td>

                            <td class="max-w-[340px]">
                                <p class="line-clamp-2 text-sm leading-6 text-[var(--color-ink)]">
                                    {{ $arsip->uraian_informasi ?? '—' }}
                                </p>
                            </td>

                            <td class="text-sm text-[var(--color-muted)]">{{ $arsip->jumlah_item ?? '—' }}</td>
                            <td class="text-sm text-[var(--color-muted)]">{{ $arsip->tanggal_arsip ?? '—' }}</td>
                            <td class="text-sm text-[var(--color-muted)]">{{ $arsip->tingkat_perkembangan ?? '—' }}</td>

                            <td>
                                @if($arsip->keterangan)
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ strtolower($arsip->keterangan) === 'terbuka' ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-[rgba(229,231,235,1)] bg-[rgba(245,239,232,1)] text-[var(--color-accent-strong)]' }}">
                                        {{ $arsip->keterangan }}
                                    </span>
                                @else
                                    <span class="text-sm text-[var(--color-muted-soft)]">—</span>
                                @endif
                            </td>

                            <td class="max-w-[170px] truncate text-sm text-[var(--color-muted)]">{{ $arsip->lokasi ?? '—' }}</td>

                            <td class="text-center">
                                <span class="inline-flex min-w-[56px] items-center justify-center rounded-full bg-[rgba(111,77,52,0.1)] px-3 py-1 font-mono text-sm font-semibold text-[var(--color-accent-strong)]">
                                    {{ $arsip->boks ?? '—' }}
                                </span>
                            </td>

                            <td class="table-frozen-end">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('arsips.edit', $arsip) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[rgba(229,231,235,1)] bg-white text-[var(--color-muted)] transition hover:border-[rgba(138,98,69,0.36)] hover:text-[rgba(111,77,52,1)]" title="Edit">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('arsips.destroy', $arsip) }}" onsubmit="return confirm('Hapus arsip secara permanen?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[rgba(164,79,61,0.18)] bg-[rgba(255,244,241,0.92)] text-[var(--color-danger)] transition hover:border-[rgba(164,79,61,0.38)]" title="Hapus">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-md">
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[rgba(245,239,232,1)] text-[var(--color-accent-strong)]">
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586A2 2 0 0114 3.586L18.414 8A2 2 0 0119 9.414V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="mt-5 text-xl font-semibold text-[var(--color-ink)]">Belum ada arsip yang cocok</h4>
                                    <p class="mt-3 text-sm leading-7 text-[var(--color-muted)]">
                                        Coba ubah filter pencarian atau tambahkan rekaman baru untuk mulai membangun database arsip.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="page-shell rounded-[28px] px-4 py-4 sm:px-6">
        {{ $arsips->links('pagination::tailwind') }}
    </div>
</div>
@endsection
