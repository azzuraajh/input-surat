@extends('layouts.app')

@section('content')
<div class="fade-up grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
    <section class="page-shell overflow-hidden rounded-[28px]">
        <div class="border-b border-[rgba(229,231,235,1)] px-6 py-8 sm:px-8 sm:py-9">
            <div class="relative max-w-2xl">
                <a href="{{ route('arsips.index') }}" class="status-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke database
                </a>

                <div class="mt-5">
                    <span class="section-kicker">Mass Import Excel</span>
                    <h2 class="display-title mt-3 text-3xl leading-tight text-[var(--color-ink)] sm:text-[2.6rem]">
                        Import data arsip massal
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-[var(--color-muted)] sm:text-base">
                        Halaman ini dipakai untuk migrasi atau penambahan data dalam jumlah besar dari spreadsheet `.xlsx` atau `.xls`.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('arsips.import.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="form-label">Pilih file spreadsheet</label>
                    <label for="file" class="flex cursor-pointer flex-col items-center justify-center rounded-[30px] border border-dashed border-[rgba(209,213,219,1)] bg-white px-6 py-12 text-center transition hover:border-[rgba(138,98,69,0.5)] hover:bg-[rgba(248,250,252,1)]">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[rgba(245,239,232,1)] text-[var(--color-accent-strong)]">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="mt-4 text-lg font-semibold text-[var(--color-ink)]">Klik untuk memilih file</p>
                        <p class="mt-2 text-sm leading-6 text-[var(--color-muted)]">Maksimal 10MB, mendukung format `.xlsx` dan `.xls`.</p>
                        <p id="file-name" class="mt-4 hidden rounded-full bg-[rgba(245,239,232,1)] px-4 py-2 text-sm font-medium text-[var(--color-accent-strong)]"></p>
                    </label>
                    <input
                        id="file"
                        type="file"
                        name="file"
                        accept=".xlsx,.xls"
                        class="sr-only"
                        onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? ''; document.getElementById('file-name').classList.toggle('hidden', !this.files[0])"
                    >
                    @error('file') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Tindakan modifikasi</label>
                    <div class="space-y-3">
                        <label class="flex cursor-pointer items-start gap-4 rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-4 transition has-[:checked]:border-[rgba(138,98,69,0.5)] has-[:checked]:bg-[rgba(245,239,232,0.5)]">
                            <input type="radio" name="mode" value="append" checked class="mt-1 h-4 w-4 border-[rgba(209,213,219,1)] text-[rgba(111,77,52,1)] focus:ring-[rgba(111,77,52,0.18)]">
                            <div>
                                <p class="font-semibold text-[var(--color-ink)]">Tambah atau perbarui data</p>
                                <p class="mt-1 text-sm leading-6 text-[var(--color-muted)]">
                                    Rekaman baru akan ditambahkan, dan data lama dengan nomor arsip yang sama akan diperbarui.
                                </p>
                            </div>
                        </label>

                        <label class="flex cursor-pointer items-start gap-4 rounded-[24px] border border-[rgba(164,79,61,0.16)] bg-[rgba(255,244,241,0.72)] p-4 transition has-[:checked]:border-[rgba(164,79,61,0.42)] has-[:checked]:bg-[rgba(255,240,236,0.92)]">
                            <input type="radio" name="mode" value="replace" class="mt-1 h-4 w-4 border-[rgba(164,79,61,0.45)] text-[rgba(164,79,61,1)] focus:ring-[rgba(164,79,61,0.3)]">
                            <div>
                                <p class="font-semibold text-[var(--color-danger)]">Ganti seluruh isi tabel</p>
                                <p class="mt-1 text-sm leading-6 text-[rgba(164,79,61,0.82)]">
                                    Semua data arsip yang ada akan dihapus permanen dan diganti dengan isi file baru.
                                </p>
                            </div>
                        </label>
                    </div>
                    @error('mode') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-[rgba(229,231,235,1)] pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('arsips.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-primary">
                        Mulai Impor
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <div class="space-y-6">
        <section class="soft-panel rounded-[28px] p-6 sm:p-7">
            <span class="section-kicker">Panduan Singkat</span>
            <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Alur import yang disarankan</h3>

            <div class="mt-6 space-y-4">
                <div class="rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--color-muted-soft)]">01</p>
                    <p class="mt-2 text-lg font-semibold text-[var(--color-ink)]">Cek struktur kolom</p>
                    <p class="mt-2 text-sm leading-6 text-[var(--color-muted)]">Pastikan spreadsheet mengikuti struktur template yang dipakai sistem.</p>
                </div>

                <div class="rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--color-muted-soft)]">02</p>
                    <p class="mt-2 text-lg font-semibold text-[var(--color-ink)]">Pilih mode dengan hati-hati</p>
                    <p class="mt-2 text-sm leading-6 text-[var(--color-muted)]">Gunakan mode tambah untuk sinkronisasi biasa, dan mode ganti total hanya saat benar-benar diperlukan.</p>
                </div>

                <div class="rounded-[24px] border border-[rgba(229,231,235,1)] bg-white p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[var(--color-muted-soft)]">03</p>
                    <p class="mt-2 text-lg font-semibold text-[var(--color-ink)]">Verifikasi hasil import</p>
                    <p class="mt-2 text-sm leading-6 text-[var(--color-muted)]">Setelah proses selesai, periksa beberapa rekaman penting untuk memastikan lokasi, box, dan nomor arsip terbaca benar.</p>
                </div>
            </div>
        </section>

        <section class="soft-panel rounded-[28px] p-6 sm:p-7">
            <span class="section-kicker">Spesifikasi File</span>
            <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Header yang dibaca sistem</h3>

            <div class="mt-6 rounded-[26px] border border-[rgba(229,231,235,1)] bg-white p-5">
                <p class="text-sm leading-7 text-[var(--color-muted)]">
                    Sistem membaca kolom mulai dari:
                    <span class="font-semibold text-[var(--color-ink)]">A (No. Urut)</span>,
                    <span class="font-semibold text-[var(--color-ink)]">B (Kode)</span>,
                    <span class="font-semibold text-[var(--color-ink)]">C (No. Arsip)</span>,
                    <span class="font-semibold text-[var(--color-ink)]">H (Jumlah Item)</span>, dan
                    <span class="font-semibold text-[var(--color-ink)]">M (Boks)</span>.
                </p>
                <p class="mt-4 text-sm leading-7 text-[var(--color-muted)]">
                    Sel gabungan di awal sheet juga didukung dan akan diteruskan ke bawah secara otomatis.
                </p>
            </div>
        </section>
    </div>
</div>
@endsection
