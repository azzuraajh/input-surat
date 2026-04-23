@extends('layouts.app')
@section('content')
<div class="fade-up mx-auto max-w-[1100px] space-y-6">
    <section class="page-shell rounded-[28px] p-6 sm:p-8 lg:p-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <a href="{{ route('arsips.index') }}" class="status-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke database
                </a>
                <div class="mt-5">
                    <span class="section-kicker">Entri Baru</span>
                    <h2 class="display-title mt-3 text-3xl leading-tight text-[var(--color-ink)] sm:text-[2.6rem]">
                        Tambahkan rekaman arsip
                    </h2>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-[var(--color-muted)] sm:text-base">
                        Isi identitas arsip, kandungan informasi, serta lokasi penyimpanan agar pencarian dan pelacakan boks lebih mudah dilakukan.
                    </p>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:w-[360px]">
                <div class="metric-card">
                    <span class="metric-label">Validasi</span>
                    <p class="text-base font-semibold text-[var(--color-ink)]">Nomor arsip dan boks diprioritaskan agar tidak rancu.</p>
                </div>
                <div class="metric-card">
                    <span class="metric-label">Kelengkapan</span>
                    <p class="text-base font-semibold text-[var(--color-ink)]">Metadata inti ditata per kelompok supaya lebih cepat diisi.</p>
                </div>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('arsips.store') }}" class="space-y-6">
        @include('arsips._form', ['arsip' => $arsip, 'kodeList' => $kodeList, 'uraianList' => $uraianList, 'backUrl' => route('arsips.index')])
    </form>
</div>
@endsection
