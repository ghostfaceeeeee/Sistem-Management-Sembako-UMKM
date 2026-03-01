<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Statistik
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-3xl border border-slate-700/80 bg-slate-900/95 backdrop-blur-md p-6">
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="text-2xl font-bold text-white">Arus Stok Tahun {{ $selectedYear }}</h3>
                        <p class="mt-1 text-sm text-white/90">Perbandingan stok masuk dan stok keluar per bulan.</p>
                    </div>

                    <form method="GET" action="{{ route('statistics.index') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2 w-full sm:w-auto">
                        <div>
                            <label for="year" class="block text-xs font-semibold text-white mb-1">Tahun</label>
                            <select id="year" name="year" onchange="this.form.submit()"
                                class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-2 text-sm font-semibold text-white focus:border-yellow-400 focus:outline-none">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" @selected($year === $selectedYear)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="chart_type" class="block text-xs font-semibold text-white mb-1">Diagram</label>
                            <select id="chart_type" name="chart"
                                class="w-full rounded-lg border border-slate-600 bg-slate-950 px-3 py-2 text-sm font-semibold text-white focus:border-yellow-400 focus:outline-none">
                                <option value="bar" @selected($selectedChart === 'bar')>Batang</option>
                                <option value="line" @selected($selectedChart === 'line')>Garis</option>
                                <option value="pie" @selected($selectedChart === 'pie')>Lingkaran</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="mt-6 h-[340px] rounded-2xl border border-slate-700/80 bg-slate-950/90 p-3">
                    <canvas id="stockFlowChart"></canvas>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function () {
            const monthly = @json($monthlyStockFlow);
            const initialChartType = @json($selectedChart);
            const labels = monthly.map(item => item.label);
            const stockIn = monthly.map(item => item.in);
            const stockOut = monthly.map(item => item.out);
            const totalIn = stockIn.reduce((sum, value) => sum + value, 0);
            const totalOut = stockOut.reduce((sum, value) => sum + value, 0);

            const root = document.documentElement;
            const isDark = root.classList.contains('theme-dark');

            const chartText = '#f8fafc';
            const chartGrid = 'rgba(203, 213, 225, 0.22)';
            const tooltipBg = isDark ? 'rgba(2, 6, 23, 0.95)' : 'rgba(255, 255, 255, 0.98)';
            const tooltipText = isDark ? '#f8fafc' : '#0f172a';

            const ctx = document.getElementById('stockFlowChart');
            if (!ctx) {
                return;
            }

            Chart.defaults.color = chartText;
            Chart.defaults.font.family = 'Space Grotesk, sans-serif';
            Chart.defaults.font.size = 13;

            let chartInstance = null;
            const chartTypeSelect = document.getElementById('chart_type');

            const defaultPlugins = {
                legend: {
                    labels: {
                        color: chartText,
                        font: {
                            size: 14,
                            weight: '800'
                        },
                        boxWidth: 14,
                        boxHeight: 14,
                        padding: 14
                    }
                },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: tooltipText,
                    bodyColor: tooltipText,
                    borderColor: isDark ? 'rgba(250, 204, 21, 0.45)' : 'rgba(15, 23, 42, 0.18)',
                    borderWidth: 1,
                    titleFont: {
                        size: 13,
                        weight: '700'
                    },
                    bodyFont: {
                        size: 13,
                        weight: '600'
                    }
                }
            };

            const renderChart = function (chartType) {
                if (chartInstance) {
                    chartInstance.destroy();
                }

                const isPie = chartType === 'pie';
                const data = isPie
                    ? {
                        labels: ['Total Stok Masuk', 'Total Stok Keluar'],
                        datasets: [
                            {
                                data: [totalIn, totalOut],
                                backgroundColor: ['rgba(34, 197, 94, 0.8)', 'rgba(239, 68, 68, 0.8)'],
                                borderColor: isDark ? '#0f172a' : '#ffffff',
                                borderWidth: 2
                            }
                        ]
                    }
                    : {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Stok Masuk',
                                data: stockIn,
                                backgroundColor: 'rgba(34, 197, 94, 0.75)',
                                borderColor: 'rgba(22, 163, 74, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
                                tension: 0.35
                            },
                            {
                                label: 'Stok Keluar',
                                data: stockOut,
                                backgroundColor: 'rgba(239, 68, 68, 0.75)',
                                borderColor: 'rgba(220, 38, 38, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
                                tension: 0.35
                            }
                        ]
                    };

                chartInstance = new Chart(ctx, {
                    type: chartType,
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: isPie ? {} : {
                            x: {
                                ticks: {
                                    color: chartText,
                                    font: {
                                        size: 13,
                                        weight: '700'
                                    }
                                },
                                grid: { color: chartGrid }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: chartText,
                                    font: {
                                        size: 13,
                                        weight: '700'
                                    }
                                },
                                grid: { color: chartGrid }
                            }
                        },
                        plugins: defaultPlugins
                    }
                });
            };

            renderChart(initialChartType);

            if (chartTypeSelect) {
                chartTypeSelect.addEventListener('change', function () {
                    const selectedType = this.value;
                    renderChart(selectedType);

                    const url = new URL(window.location.href);
                    url.searchParams.set('chart', selectedType);
                    history.replaceState(null, '', url.toString());
                });
            }
        })();
    </script>
</x-app-layout>
