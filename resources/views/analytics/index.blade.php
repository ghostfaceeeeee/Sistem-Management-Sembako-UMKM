<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Analitik
        </h2>
    </x-slot>

    @php
        $baseQuery = [
            'year' => $selectedYear,
            'trend' => $trendUnit,
            'dead_days' => $deadDays,
        ];
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                <form method="GET" action="{{ route('analytics.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">

                    <div>
                        <label for="year" class="mb-1 block text-xs font-semibold">Tahun Analisis</label>
                        <select id="year" name="year"
                            class="w-full rounded-xl border border-slate-600 bg-slate-950 px-3 py-2.5 text-sm font-semibold text-white">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" @selected($selectedYear === $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="trend" class="mb-1 block text-xs font-semibold">Tren Penjualan</label>
                        <select id="trend" name="trend"
                            class="w-full rounded-xl border border-slate-600 bg-slate-950 px-3 py-2.5 text-sm font-semibold text-white">
                            <option value="week" @selected($trendUnit === 'week')>Mingguan</option>
                            <option value="month" @selected($trendUnit === 'month')>Bulanan</option>
                            <option value="year" @selected($trendUnit === 'year')>Tahunan</option>
                        </select>
                    </div>

                    <div>
                        <label for="dead_days" class="mb-1 block text-xs font-semibold">Dead Stock (Hari)</label>
                        <input id="dead_days" name="dead_days" type="number" min="7" max="365"
                            value="{{ $deadDays }}"
                            class="w-full rounded-xl border border-slate-600 bg-slate-950 px-3 py-2.5 text-sm font-semibold text-white">
                    </div>

                    <div class="md:col-span-3">
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-yellow-300 px-5 py-2.5 text-sm font-semibold text-black transition hover:brightness-95">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-3 text-white dark:border-slate-700 dark:bg-slate-950/85">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('analytics.index', array_merge($baseQuery, ['tab' => 'overview'])) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold {{ $activeTab === 'overview' ? 'bg-yellow-300 text-black' : 'bg-slate-800 text-white hover:bg-slate-700' }}">
                        Overview
                    </a>
                    <a href="{{ route('analytics.index', array_merge($baseQuery, ['tab' => 'profit'])) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold {{ $activeTab === 'profit' ? 'bg-yellow-300 text-black' : 'bg-slate-800 text-white hover:bg-slate-700' }}">
                        Profit & Loss
                    </a>
                    <a href="{{ route('analytics.index', array_merge($baseQuery, ['tab' => 'dead-stock'])) }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold {{ $activeTab === 'dead-stock' ? 'bg-yellow-300 text-black' : 'bg-slate-800 text-white hover:bg-slate-700' }}">
                        Dead Stock
                    </a>
                </div>
            </section>

            @if($activeTab === 'overview')
                <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <article class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                        <h3 class="text-lg font-bold">Produk Terlaris ({{ $selectedYear }})</h3>
                        <div class="mt-3 space-y-2">
                            @forelse($topProducts as $product)
                                <div class="flex items-center justify-between rounded-xl border border-slate-700 px-3 py-2">
                                    <span class="font-semibold">{{ $product->nama_barang }}</span>
                                    <span class="text-sm font-bold">{{ (int) $product->qty_out }} keluar</span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-300">Belum ada data pergerakan.</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                        <h3 class="text-lg font-bold">Produk Jarang Bergerak ({{ $selectedYear }})</h3>
                        <div class="mt-3 space-y-2">
                            @forelse($slowMovingProducts as $product)
                                <div class="flex items-center justify-between rounded-xl border border-slate-700 px-3 py-2">
                                    <span class="font-semibold">{{ $product->nama_barang }}</span>
                                    <span class="text-sm font-bold">{{ (int) $product->qty_out }} keluar</span>
                                </div>
                            @empty
                                <p class="text-sm text-slate-300">Belum ada data pergerakan.</p>
                            @endforelse
                        </div>
                    </article>
                </section>

                <section class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                    <h3 class="text-lg font-bold">Tren Penjualan (Stok Keluar)</h3>
                    <p class="mt-1 text-sm text-white/85">Granularitas mengikuti filter: mingguan / bulanan / tahunan.</p>
                    <div class="mt-4 h-[320px] rounded-xl border border-slate-700 bg-slate-950/90 p-3">
                        <canvas id="salesTrendChart"></canvas>
                    </div>
                </section>

                <section class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                    <h3 class="text-lg font-bold">Performa Supplier ({{ $selectedYear }})</h3>
                    <div class="mt-4 h-[320px] rounded-xl border border-slate-700 bg-slate-950/90 p-3">
                        <canvas id="supplierChart"></canvas>
                    </div>
                    <div class="mt-4 overflow-x-auto rounded-xl bg-white p-2">
                        <div class="max-h-[360px] overflow-auto">
                        <table class="min-w-full text-sm text-black">
                            <thead>
                                <tr class="text-left">
                                    <th class="sticky top-0 z-10 bg-white py-2">Supplier</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Qty Keluar</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Revenue</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplierPerformance as $supplier)
                                    <tr class="border-t border-slate-200/70">
                                        <td class="py-2 font-semibold">{{ $supplier->nama_supplier }}</td>
                                        <td class="py-2">{{ (int) $supplier->sold_qty }}</td>
                                        <td class="py-2">Rp {{ number_format((float) $supplier->revenue, 0, ',', '.') }}</td>
                                        <td class="py-2">Rp {{ number_format((float) $supplier->profit, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 text-slate-700">Belum ada data supplier pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                </section>
            @endif

            @if($activeTab === 'profit')
                <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <article class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                        <p class="text-xs uppercase tracking-wide text-white">Total Revenue</p>
                        <p class="mt-2 text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </article>
                    <article class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                        <p class="text-xs uppercase tracking-wide text-white">Total COGS</p>
                        <p class="mt-2 text-2xl font-bold">Rp {{ number_format($totalCogs, 0, ',', '.') }}</p>
                    </article>
                    <article class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                        <p class="text-xs uppercase tracking-wide text-white">Total Profit</p>
                        <p class="mt-2 text-2xl font-bold {{ $totalProfit >= 0 ? 'text-emerald-300' : 'text-red-300' }}">
                            Rp {{ number_format($totalProfit, 0, ',', '.') }}
                        </p>
                    </article>
                </section>

                <section class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                    <h3 class="text-lg font-bold">Produk Paling Menguntungkan ({{ $selectedYear }})</h3>
                    @if($mostProfitableProducts->isEmpty())
                        <div class="mt-4 rounded-xl border border-dashed border-slate-500/70 bg-slate-800/60 px-4 py-6 text-sm text-slate-200">
                            Belum ada data profit pada tahun {{ $selectedYear }}. Coba ubah filter tahun atau mulai catat transaksi stok keluar.
                        </div>
                    @else
                    <div class="mt-4 overflow-x-auto rounded-xl bg-white p-2">
                        <div class="max-h-[380px] overflow-auto">
                        <table class="min-w-full text-sm text-black">
                            <thead>
                                <tr class="text-left">
                                    <th class="sticky top-0 z-10 bg-white py-2">Produk</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Qty Terjual</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Revenue</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mostProfitableProducts as $row)
                                    <tr class="border-t border-slate-200/70">
                                        <td class="py-2 font-semibold">{{ $row->nama_barang }}</td>
                                        <td class="py-2">{{ (int) $row->sold_qty }}</td>
                                        <td class="py-2">Rp {{ number_format((float) $row->revenue, 0, ',', '.') }}</td>
                                        <td class="py-2">Rp {{ number_format((float) $row->profit, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 text-slate-700">Belum ada data profit pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @endif
                </section>
            @endif

            @if($activeTab === 'dead-stock')
                <section class="rounded-2xl border border-slate-700/80 bg-slate-900/95 p-5 text-white dark:border-slate-700 dark:bg-slate-950/85">
                    <h3 class="text-lg font-bold">Dead Stock Alert (>= {{ $deadDays }} hari)</h3>
                    <p class="mt-1 text-sm text-white">Produk yang tidak bergerak dalam batas hari yang dipilih.</p>
                    @if(collect($deadStockProducts)->isEmpty())
                        <div class="mt-4 rounded-xl border border-dashed border-slate-500/70 bg-slate-800/60 px-4 py-6 text-sm text-slate-200">
                            Tidak ada dead stock pada ambang {{ $deadDays }} hari. Stok kamu sedang sehat.
                        </div>
                    @else
                    <div class="mt-4 overflow-x-auto rounded-xl bg-white p-2">
                        <div class="max-h-[420px] overflow-auto">
                        <table class="min-w-full text-sm text-black">
                            <thead>
                                <tr class="text-left">
                                    <th class="sticky top-0 z-10 bg-white py-2">Produk</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Terakhir Bergerak</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Idle (Hari)</th>
                                    <th class="sticky top-0 z-10 bg-white py-2">Rekomendasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deadStockProducts as $item)
                                    <tr class="border-t border-slate-200/70">
                                        <td class="py-2 font-semibold">{{ $item['nama_barang'] }}</td>
                                        <td class="py-2">{{ $item['last_moved_at'] ? $item['last_moved_at']->format('d M Y') : 'Belum pernah bergerak' }}</td>
                                        <td class="py-2">{{ $item['days_idle'] ?? '-' }}</td>
                                        <td class="py-2">{{ $item['recommendation'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-3 text-slate-700">Tidak ada dead stock untuk ambang hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    </div>
                    @endif
                </section>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const activeTab = @json($activeTab);
            if (activeTab !== 'overview') {
                return;
            }

            const labels = @json($trendLabels);
            const values = @json($trendValues);
            const supplierRows = @json($supplierPerformance);

            const textColor = '#f8fafc';
            const gridColor = 'rgba(148, 163, 184, 0.22)';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = 'Space Grotesk, sans-serif';
            Chart.defaults.font.size = 13;

            const salesCtx = document.getElementById('salesTrendChart');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Qty Keluar',
                            data: values,
                            borderColor: 'rgba(250, 204, 21, 1)',
                            backgroundColor: 'rgba(250, 204, 21, 0.25)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointRadius: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { grid: { color: gridColor } },
                            y: { beginAtZero: true, grid: { color: gridColor } }
                        }
                    }
                });
            }

            const supplierCtx = document.getElementById('supplierChart');
            if (supplierCtx) {
                new Chart(supplierCtx, {
                    type: 'bar',
                    data: {
                        labels: supplierRows.map(item => item.nama_supplier),
                        datasets: [{
                            label: 'Profit Supplier',
                            data: supplierRows.map(item => Number(item.profit)),
                            backgroundColor: 'rgba(34, 197, 94, 0.72)',
                            borderColor: 'rgba(22, 163, 74, 1)',
                            borderWidth: 2,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { grid: { color: gridColor } },
                            y: { beginAtZero: true, grid: { color: gridColor } }
                        }
                    }
                });
            }
        })();
    </script>
</x-app-layout>
