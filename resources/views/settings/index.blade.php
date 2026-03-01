<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Settings
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-1">Appearance</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Gunakan switch untuk mengganti tema.
                </p>

                <div class="flex items-center justify-between rounded-lg border border-cyan-300/20 p-4 bg-slate-900/20">
                    <div>
                        <p class="font-medium text-gray-800">Dark Mode</p>
                        <p class="text-sm text-gray-500">Aktifkan tampilan gelap futuristik.</p>
                    </div>

                    <label class="iphone-switch" for="theme-toggle" title="Toggle theme">
                        <input type="checkbox" id="theme-toggle" aria-label="Toggle dark mode">
                        <span class="iphone-switch-slider"></span>
                    </label>
                </div>

                <div class="mt-4 text-sm">
                    <span class="text-gray-500">Current theme:</span>
                    <span class="font-semibold text-gray-800" id="theme-label">Dark</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('theme-toggle');
            const label = document.getElementById('theme-label');
            if (!toggle || !label) return;

            const applyThemeFallback = function (theme) {
                const normalized = theme === 'light' ? 'light' : 'dark';
                document.documentElement.classList.remove('theme-dark', 'theme-light');
                document.documentElement.classList.add('theme-' + normalized);
                document.documentElement.setAttribute('data-theme', normalized);
                localStorage.setItem('app-theme', normalized);
            };

            const applyTheme = function (theme) {
                if (typeof window.setAppTheme === 'function') {
                    window.setAppTheme(theme);
                } else {
                    applyThemeFallback(theme);
                }
            };

            const current = localStorage.getItem('app-theme') === 'light' ? 'light' : 'dark';
            toggle.checked = current === 'dark';
            label.textContent = toggle.checked ? 'Dark' : 'Light';

            toggle.addEventListener('change', function () {
                const theme = toggle.checked ? 'dark' : 'light';
                applyTheme(theme);
                label.textContent = toggle.checked ? 'Dark' : 'Light';
            });
        })();
    </script>
</x-app-layout>
