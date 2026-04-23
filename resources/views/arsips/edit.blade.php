@extends('layouts.app')
@section('content')
<div class="fade-up mx-auto max-w-[1100px] space-y-6">
    <section class="page-shell rounded-[28px] p-6 sm:p-8 lg:p-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <a href="{{ route('arsips.show', $arsip) }}" class="status-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke detail
                </a>
                <div class="mt-5">
                    <span class="section-kicker">Pembaruan Arsip</span>
                    <h2 class="display-title mt-3 text-3xl leading-tight text-[var(--color-ink)] sm:text-[2.6rem]">
                        Perbarui data arsip
                    </h2>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-[var(--color-muted)] sm:text-base">
                        Rapikan metadata, sesuaikan lokasi penyimpanan, dan jaga konsistensi nomor arsip agar rekaman tetap mudah ditelusuri.
                    </p>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:w-[360px]">
                <div class="metric-card">
                    <span class="metric-label">Nomor Arsip</span>
                    <p class="text-lg font-semibold text-[var(--color-ink)]">{{ $arsip->nomor_arsip }}</p>
                </div>
                <div class="metric-card">
                    <span class="metric-label">Fokus Update</span>
                    <p class="text-base font-semibold text-[var(--color-ink)]">Pastikan identitas dan lokasi tetap sinkron.</p>
                </div>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('arsips.update', $arsip) }}" class="space-y-6">
        @method('PUT')
        @include('arsips._form', ['arsip' => $arsip, 'kodeList' => $kodeList, 'uraianList' => $uraianList, 'backUrl' => route('arsips.show', $arsip)])
    </form>
</div>
@endsection
