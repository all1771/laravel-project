<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ледовый каток') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito-sans:400,600,700&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Nunito Sans', 'system-ui', 'sans-serif'] } } } }</script>
    @endif
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/80">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 transition opacity-90 hover:opacity-100">
                <span class="text-xl font-bold text-sky-800">Каток</span>
            </a>
            <nav class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('home') }}#about" class="hidden rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 sm:block">О катке</a>
                <a href="{{ route('booking.index') }}" class="rounded-lg px-3 py-2 text-sm text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Бронирование</a>
                <a href="{{ url('/admin') }}" class="rounded-lg px-3 py-2 text-sm text-slate-500 transition hover:bg-slate-100 hover:text-slate-700" title="Админ-панель">Админка</a>
                <a href="{{ route('ticket.index') }}" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 active:scale-[0.98]">
                    Купить билет
                </a>
            </nav>
        </div>
    </header>

    <main class="min-h-[calc(100vh-4rem)]">
        @if(session('error'))
            <div class="mx-auto max-w-6xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-8">
        <div class="mx-auto max-w-6xl px-4 text-center text-sm text-slate-500 sm:px-6 lg:px-8">
            Ледовый каток. Бронирование коньков и билетов.
        </div>
    </footer>

    @stack('scripts')
    @if (!file_exists(public_path('build/manifest.json')))
    <script>
        document.querySelectorAll('input[name="phone"], input[name="customer_phone"]').forEach(function(input) {
            input.addEventListener('input', function(e) {
                var v = e.target.value.replace(/\D/g, '');
                if (v.startsWith('8') || v.startsWith('7')) v = v.slice(1);
                v = v.slice(0, 10);
                var s = '+7';
                if (v.length > 0) s += ' (' + v.slice(0, 3);
                if (v.length >= 3) s += ') ' + v.slice(3, 6);
                if (v.length >= 6) s += '-' + v.slice(6, 8);
                if (v.length >= 8) s += '-' + v.slice(8, 10);
                e.target.value = s;
            });
            input.addEventListener('focus', function(e) { if (e.target.value === '') e.target.value = '+7 ('; });
            input.addEventListener('blur', function(e) { if (e.target.value === '+7 (' || e.target.value === '+7') e.target.value = ''; });
        });
    </script>
    @endif
</body>
</html>
