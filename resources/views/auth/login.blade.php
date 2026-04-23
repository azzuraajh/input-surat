@extends('layouts.app')

@section('content')
<div class="fade-up mx-auto grid max-w-[1180px] gap-6 lg:min-h-[calc(100vh-10rem)] lg:grid-cols-[0.95fr_1.05fr]">
    <section class="page-shell overflow-hidden rounded-[28px]">
        <div class="relative h-full min-h-[320px]">
            <img
                src="{{ asset('images/building.jpg') }}"
                alt="Gedung kantor arsip"
                class="absolute inset-0 h-full w-full object-cover"
            >
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.14)_0%,rgba(255,255,255,0.34)_100%)]"></div>

            <div class="relative flex h-full flex-col justify-end p-6 sm:p-8">
                <div class="max-w-lg rounded-[22px] border border-white/70 bg-white/88 p-5 text-[var(--color-ink)] shadow-[0_12px_30px_rgba(15,23,42,0.08)] backdrop-blur-sm">
                    <span class="section-kicker">Arsip Digital</span>
                    <h2 class="mt-3 text-2xl font-semibold leading-tight sm:text-[2rem]">
                        Sistem pengelolaan arsip internal Setditjen KSDAE.
                    </h2>
                    <p class="mt-3 text-sm leading-7 text-[var(--color-muted)]">
                        Tampilan dibuat lebih tenang agar pencarian data, input metadata, dan import Excel terasa lebih fokus.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-shell rounded-[28px] p-6 sm:p-8 lg:p-10">
        <div class="mx-auto flex h-full w-full max-w-md items-center">
            <div class="w-full">
                <span class="status-pill">
                    <span class="h-2 w-2 rounded-full bg-[rgba(138,98,69,1)]"></span>
                    Login administrator
                </span>

                <h1 class="display-title mt-5 text-3xl leading-tight text-[var(--color-ink)] sm:text-[2.8rem]">
                    Masuk ke dashboard arsip
                </h1>
                <p class="mt-3 text-sm leading-7 text-[var(--color-muted)] sm:text-base">
                    Gunakan akun pegawai yang memiliki otorisasi untuk mengelola data arsip digital.
                </p>

                @if ($errors->any())
                    <div class="mt-6 rounded-[18px] border border-[rgba(164,79,61,0.18)] bg-[rgba(255,244,241,0.92)] p-4 text-sm text-[var(--color-danger)]">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                            <p class="font-medium">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            required
                            autofocus
                            placeholder="nama@instansi.go.id"
                            class="form-input"
                        >
                    </div>

                    <div>
                        <label for="password" class="form-label">Kata sandi</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="Masukkan kata sandi"
                            class="form-input"
                        >
                    </div>

                    <label class="inline-flex items-center gap-3 pt-1 text-sm font-medium text-[var(--color-muted)]">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            @checked(old('remember'))
                            class="h-4 w-4 rounded border-[rgba(209,213,219,1)] text-[rgba(111,77,52,1)] focus:ring-[rgba(111,77,52,0.18)]"
                        >
                        Ingat sesi saya
                    </label>

                    <button type="submit" class="btn-primary w-full justify-between px-5 py-3.5 text-base">
                        <span>Masuk</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>

                <div class="mt-8 border-t border-[rgba(229,231,235,1)] pt-5 text-sm text-[var(--color-muted)]">
                    Akses ini digunakan untuk pencarian arsip, pembaruan metadata, dan import data massal.
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
