<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Arsip Digital Setditjen KSDAE' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php($isAuthPage = request()->routeIs('login') || request()->routeIs('login.store'))
<body class="antialiased">
    <div class="relative min-h-screen">
        <header class="sticky top-0 z-50 border-b border-[rgba(229,231,235,1)] bg-white/95 backdrop-blur-sm">
            <div class="mx-auto flex h-[4.5rem] w-full max-w-[1380px] items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl border border-[rgba(229,231,235,1)] bg-white">
                        <span class="text-lg font-semibold text-[var(--color-accent-strong)]">AD</span>
                    </div>
                    <div>
                        <span class="section-kicker">Portal Arsip Internal</span>
                        <div class="mt-1 flex items-center gap-3">
                            <h1 class="text-sm font-semibold tracking-[0.01em] text-[var(--color-ink)] sm:text-base">ArsipDigital KSDAE</h1>
                            <span class="hidden h-1 w-1 rounded-full bg-[rgba(111,77,52,0.28)] sm:block"></span>
                            <span class="hidden text-sm text-[var(--color-muted)] sm:block">Setditjen KSDAE</span>
                        </div>
                    </div>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        <span class="status-pill hidden sm:inline-flex">
                            <span class="h-2 w-2 rounded-full bg-[rgba(138,98,69,1)]"></span>
                            {{ auth()->user()->email }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="btn-secondary px-4 py-2 text-sm" type="submit">Logout</button>
                        </form>
                    @else
                        <span class="status-pill hidden sm:inline-flex">
                            <span class="h-2 w-2 rounded-full bg-[rgba(138,98,69,1)]"></span>
                            Arsip digital internal
                        </span>
                    @endauth
                </div>
            </div>
        </header>

        <main class="relative z-10 mx-auto w-full max-w-[1380px] px-4 pb-10 pt-6 sm:px-6 lg:px-8 {{ $isAuthPage ? 'lg:pb-12 lg:pt-8' : 'lg:pb-14 lg:pt-8' }}">
            @if (session('status'))
                <div class="alert-success mb-6 flex items-center gap-3 rounded-[22px] px-4 py-4 text-sm font-medium">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
