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
        <div class="min-h-screen endfield-shell">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="endfield-header border-b border-cyan-400/20">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 endfield-header-inner">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-0">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
