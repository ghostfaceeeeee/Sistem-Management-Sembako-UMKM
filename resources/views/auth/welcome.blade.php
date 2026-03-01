<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Welcome - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

        <script>
            (function () {
                const applyTheme = function (theme) {
                    const normalized = theme === 'light' ? 'light' : 'dark';
                    document.documentElement.classList.remove('theme-dark', 'theme-light');
                    document.documentElement.classList.add('theme-' + normalized);
                    localStorage.setItem('app-theme', normalized);
                    document.documentElement.setAttribute('data-theme', normalized);
                };

                window.setAppTheme = applyTheme;

                const saved = localStorage.getItem('app-theme');
                applyTheme(saved === 'light' ? 'light' : 'dark');
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased endfield-theme">
        <div class="min-h-screen endfield-shell flex items-center justify-center px-4">
            <div class="w-full max-w-xl rounded-3xl border border-white/20 bg-white/70 backdrop-blur-xl shadow-2xl p-8 text-center dark:bg-slate-900/70 dark:border-white/10">
                <p class="text-xs tracking-[0.24em] uppercase text-slate-600 dark:text-slate-300">System Access</p>
                <h1 class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="mt-3 text-slate-700 dark:text-slate-200">
                    Menyiapkan panel inventory untuk Anda.
                    <span id="countdown">3</span> detik lagi masuk ke dashboard.
                </p>

                <div class="mt-6 flex items-center justify-center gap-3">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center rounded-xl bg-black px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 dark:bg-yellow-300 dark:text-black">
                        Lewati
                    </a>
                    <button type="button" id="cancel-redirect"
                        class="inline-flex items-center rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-800">
                        Tetap di sini
                    </button>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const countdownEl = document.getElementById('countdown');
                const cancelBtn = document.getElementById('cancel-redirect');
                const targetUrl = @json(route('home'));
                let remaining = 3;
                let cancelled = false;

                const intervalId = window.setInterval(function () {
                    if (cancelled) {
                        window.clearInterval(intervalId);
                        return;
                    }

                    remaining -= 1;
                    if (remaining <= 0) {
                        window.clearInterval(intervalId);
                        window.location.href = targetUrl;
                        return;
                    }

                    countdownEl.textContent = String(remaining);
                }, 1000);

                cancelBtn.addEventListener('click', function () {
                    cancelled = true;
                    countdownEl.textContent = '-';
                });
            })();
        </script>
    </body>
</html>
