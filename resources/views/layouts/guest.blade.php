<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased endfield-theme">
        <div class="min-h-screen endfield-shell flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-md">
                <div class="text-center mb-6">
                    <a href="/" class="inline-flex items-center gap-3">
                        <x-application-logo class="w-10 h-10 fill-current text-gray-800" />
                        <span class="font-semibold tracking-wide text-gray-800">UMKM Sembako</span>
                    </a>
                </div>

                <div class="bg-white shadow rounded-2xl p-6 sm:p-7 overflow-hidden">
                    {{ $slot }}
                </div>

                <p class="mt-4 text-center text-[11px] text-slate-500">
                    {{ config('app.name') }} v{{ config('app.version') }}
                </p>
            </div>
        </div>
    </body>
</html>
