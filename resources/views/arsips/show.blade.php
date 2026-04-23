@extends('layouts.app')

@section('content')
@php
    $statusIsOpen = strtolower((string) $arsip->keterangan) === 'terbuka';
@endphp

<div class="fade-up space-y-6">
    <section class="page-shell rounded-[28px]">
        <div class="relative p-6 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
                <div class="max-w-3xl">
                    <a href="{{ route('arsips.index') }}" class="status-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke database
                    </a>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <span class="status-pill">
                            <span class="h-2 w-2 rounded-full bg-[rgba(138,98,69,1)]"></span>
                            {{ $arsip->kode_klasifikasi ?? 'Tanpa kode' }}
                        </span>
                        <span class="status-pill {{ $statusIsOpen ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : '' }}">
                            <span class="h-2 w-2 rounded-full {{ $statusIsOpen ? 'bg-emerald-500' : 'bg-[rgba(138,98,69,1)]' }}"></span>
                            {{ $arsip->keterangan ?? 'Status belum diisi' }}
                        </span>
                    </div>

                    <h2 class="display-title mt-5 text-3xl leading-tight text-[var(--color-ink)] sm:text-[2.7rem]">
                        {{ $arsip->uraian_informasi ?: 'Belum ada uraian informasi arsip yang dicatat untuk dokumen ini.' }}
                    </h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-[var(--color-muted)] sm:text-base">
                        Rekaman ini menyimpan identitas arsip, lokasi penempatan, serta rincian fisik yang diperlukan untuk pelacakan dokumen internal.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 xl:max-w-[340px] xl:justify-end">
                    <a href="{{ route('arsips.edit', $arsip) }}" class="btn-secondary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit Data
                    </a>
                    <form method="POST" action="{{ route('arsips.destroy', $arsip) }}" onsubmit="return confirm('Hapus permanen arsip ini?')" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="metric-card">
                    <span class="metric-label">Nomor arsip</span>
                    <span class="metric-value font-mono text-[1.25rem] sm:text-[1.5rem]">{{ $arsip->nomor_arsip }}</span>
                    <p class="text-sm leading-6 text-[var(--color-muted)]">Nomor utama yang dipakai untuk pencarian dan referensi arsip.</p>
                </div>

                <div class="metric-card">
                    <span class="metric-label">Lokasi rak</span>
                    <span class="metric-value text-[1.1rem] sm:text-[1.3rem]">{{ $arsip->lokasi ?? 'Belum diisi' }}</span>
                    <p class="text-sm leading-6 text-[var(--color-muted)]">Posisi fisik dokumen untuk kebutuhan pelacakan cepat.</p>
                </div>

                <div class="metric-card">
                    <span class="metric-label">Boks penyimpanan</span>
                    <span class="metric-value font-mono">{{ $arsip->boks ?? '—' }}</span>
                    <p class="text-sm leading-6 text-[var(--color-muted)]">Nomor box yang menjadi referensi pengambilan dokumen.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr]">
        <section class="soft-panel rounded-[28px] p-6 sm:p-7">
            <span class="section-kicker">Ringkasan Arsip</span>
            <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Identitas dan kandungan data</h3>

            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="detail-list">
                    <div class="detail-item">
                        <span class="detail-label">Kode Klasifikasi</span>
                        <span class="detail-value">{{ $arsip->kode_klasifikasi ?? '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nomor Arsip</span>
                        <span class="detail-value font-mono">{{ $arsip->nomor_arsip }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nomor Berkas</span>
                        <span class="detail-value">{{ $arsip->nomor_berkas ?? '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jenis Dokumen</span>
                        <span class="detail-value">{{ $arsip->uraian ?? '—' }}</span>
                    </div>
                </div>

                <div class="detail-list">
                    <div class="detail-item">
                        <span class="detail-label">Kurun Waktu</span>
                        <span class="detail-value">{{ $arsip->kurun_waktu ?? '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Arsip</span>
                        <span class="detail-value">{{ $arsip->tanggal_arsip ?? '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Jumlah Item</span>
                        <span class="detail-value">{{ $arsip->jumlah_item ?? '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tingkat Perkembangan</span>
                        <span class="detail-value">{{ $arsip->tingkat_perkembangan ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 rounded-[28px] border border-[rgba(229,231,235,1)] bg-white p-5">
                <span class="detail-label">Uraian Informasi Arsip</span>
                <p class="mt-3 text-sm leading-7 text-[var(--color-ink)] sm:text-base">
                    {{ $arsip->uraian_informasi ?? 'Belum ada uraian informasi tambahan yang dicatat.' }}
                </p>
            </div>
        </section>

        <div class="space-y-6">
            <section class="soft-panel rounded-[28px] p-6 sm:p-7">
                <span class="section-kicker">Lokasi dan Status</span>
                <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Penempatan fisik</h3>

                <div class="mt-6 space-y-4">
                    <div class="rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-5">
                        <span class="detail-label">Lokasi Rak</span>
                        <p class="mt-3 text-lg font-semibold text-[var(--color-ink)]">{{ $arsip->lokasi ?? 'Belum ditentukan' }}</p>
                    </div>

                    <div class="rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-5">
                        <span class="detail-label">Nomor Boks</span>
                        <p class="mt-3 font-mono text-3xl font-semibold text-[var(--color-accent-strong)]">{{ $arsip->boks ?? '—' }}</p>
                    </div>

                    <div class="rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-5">
                        <span class="detail-label">Keterangan</span>
                        <p class="mt-3 text-lg font-semibold {{ $statusIsOpen ? 'text-emerald-700' : 'text-[var(--color-accent-strong)]' }}">
                            {{ $arsip->keterangan ?? 'Belum diisi' }}
                        </p>
                    </div>
                </div>
            </section>

            <section class="soft-panel rounded-[28px] p-6 sm:p-7">
                <span class="section-kicker">Riwayat Input</span>
                <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Audit singkat</h3>

                <div class="mt-6 detail-list">
                    <div class="detail-item">
                        <span class="detail-label">Ditambahkan</span>
                        <span class="detail-value">
                            {{ optional($arsip->created_at)->format('d M Y, H:i') ?? '—' }}
                            @if($arsip->created_at)
                                <span class="block text-sm text-[var(--color-muted)]">{{ $arsip->created_at->diffForHumans() }}</span>
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Terakhir diperbarui</span>
                        <span class="detail-value">
                            {{ optional($arsip->updated_at)->format('d M Y, H:i') ?? '—' }}
                            @if($arsip->updated_at)
                                <span class="block text-sm text-[var(--color-muted)]">{{ $arsip->updated_at->diffForHumans() }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
