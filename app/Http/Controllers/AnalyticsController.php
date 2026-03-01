<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $availableYears = StockTransaction::query()
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->map(fn ($year) => (int) $year)
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([(int) now()->year]);
        }

        $selectedYear = (int) $request->query('year', $availableYears->first());
        if (! $availableYears->contains($selectedYear)) {
            $selectedYear = (int) $availableYears->first();
        }

        $trendUnit = (string) $request->query('trend', 'month');
        if (! in_array($trendUnit, ['week', 'month', 'year'], true)) {
            $trendUnit = 'month';
        }

        $deadDays = (int) $request->query('dead_days', 30);
        $deadDays = max(7, min(365, $deadDays));

        $activeTab = (string) $request->query('tab', 'overview');
        if (! in_array($activeTab, ['overview', 'profit', 'dead-stock'], true)) {
            $activeTab = 'overview';
        }

        $yearStart = Carbon::create($selectedYear, 1, 1)->startOfDay();
        $yearEnd = Carbon::create($selectedYear, 12, 31)->endOfDay();

        $outByProductInYear = StockTransaction::query()
            ->select('product_id')
            ->selectRaw('SUM(quantity) as qty_out')
            ->where('type', 'out')
            ->whereBetween('created_at', [$yearStart, $yearEnd])
            ->groupBy('product_id');

        $topProducts = Product::query()
            ->leftJoinSub($outByProductInYear, 'out_year', function ($join) {
                $join->on('products.id', '=', 'out_year.product_id');
            })
            ->select('products.id', 'products.nama_barang')
            ->selectRaw('COALESCE(out_year.qty_out, 0) as qty_out')
            ->orderByDesc('qty_out')
            ->orderBy('products.nama_barang')
            ->limit(5)
            ->get();

        $slowMovingProducts = Product::query()
            ->leftJoinSub($outByProductInYear, 'out_year', function ($join) {
                $join->on('products.id', '=', 'out_year.product_id');
            })
            ->select('products.id', 'products.nama_barang')
            ->selectRaw('COALESCE(out_year.qty_out, 0) as qty_out')
            ->orderBy('qty_out')
            ->orderBy('products.nama_barang')
            ->limit(5)
            ->get();

        $supplierPerformance = DB::table('suppliers')
            ->leftJoin('products', 'suppliers.id', '=', 'products.supplier_id')
            ->leftJoin('stock_transactions as st', function ($join) use ($yearStart, $yearEnd) {
                $join->on('products.id', '=', 'st.product_id')
                    ->where('st.type', '=', 'out')
                    ->whereBetween('st.created_at', [$yearStart, $yearEnd]);
            })
            ->groupBy('suppliers.id', 'suppliers.nama_supplier')
            ->select(
                'suppliers.id',
                'suppliers.nama_supplier',
                DB::raw('COALESCE(SUM(st.quantity), 0) as sold_qty'),
                DB::raw('COALESCE(SUM(st.quantity * products.harga_jual), 0) as revenue'),
                DB::raw('COALESCE(SUM(st.quantity * (products.harga_jual - products.harga_beli)), 0) as profit')
            )
            ->orderByDesc('profit')
            ->limit(8)
            ->get();

        $productProfitRows = DB::table('stock_transactions as st')
            ->join('products as p', 'p.id', '=', 'st.product_id')
            ->where('st.type', 'out')
            ->whereBetween('st.created_at', [$yearStart, $yearEnd])
            ->groupBy('p.id', 'p.nama_barang')
            ->select(
                'p.id',
                'p.nama_barang',
                DB::raw('SUM(st.quantity) as sold_qty'),
                DB::raw('SUM(st.quantity * p.harga_jual) as revenue'),
                DB::raw('SUM(st.quantity * p.harga_beli) as cogs'),
                DB::raw('SUM(st.quantity * (p.harga_jual - p.harga_beli)) as profit')
            )
            ->orderByDesc('profit')
            ->get();

        $totalRevenue = (float) $productProfitRows->sum('revenue');
        $totalCogs = (float) $productProfitRows->sum('cogs');
        $totalProfit = (float) $productProfitRows->sum('profit');
        $mostProfitableProducts = $productProfitRows->take(5)->values();

        $deadLimit = now()->subDays($deadDays);
        $lastMovementSub = StockTransaction::query()
            ->select('product_id')
            ->selectRaw('MAX(created_at) as last_moved_at')
            ->groupBy('product_id');

        $deadStockProducts = Product::query()
            ->leftJoinSub($lastMovementSub, 'movement', function ($join) {
                $join->on('products.id', '=', 'movement.product_id');
            })
            ->select('products.id', 'products.nama_barang', 'movement.last_moved_at')
            ->where(function ($query) use ($deadLimit) {
                $query->whereNull('movement.last_moved_at')
                    ->orWhere('movement.last_moved_at', '<', $deadLimit);
            })
            ->orderBy('movement.last_moved_at')
            ->limit(15)
            ->get()
            ->map(function ($row) {
                $lastMovedAt = $row->last_moved_at ? Carbon::parse($row->last_moved_at) : null;
                $daysIdle = $lastMovedAt ? (int) $lastMovedAt->diffInDays(now()) : null;
                $recommendation = $daysIdle === null || $daysIdle > 90
                    ? 'Pertimbangkan retur ke supplier.'
                    : 'Pertimbangkan promo bundling/discount.';

                return [
                    'id' => $row->id,
                    'nama_barang' => $row->nama_barang,
                    'last_moved_at' => $lastMovedAt,
                    'days_idle' => $daysIdle,
                    'recommendation' => $recommendation,
                ];
            });

        [$trendLabels, $trendValues] = $this->buildTrendData($trendUnit, $selectedYear, $availableYears->all());

        return view('analytics.index', [
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
            'trendUnit' => $trendUnit,
            'deadDays' => $deadDays,
            'topProducts' => $topProducts,
            'slowMovingProducts' => $slowMovingProducts,
            'supplierPerformance' => $supplierPerformance,
            'totalRevenue' => $totalRevenue,
            'totalCogs' => $totalCogs,
            'totalProfit' => $totalProfit,
            'mostProfitableProducts' => $mostProfitableProducts,
            'deadStockProducts' => $deadStockProducts,
            'trendLabels' => $trendLabels,
            'trendValues' => $trendValues,
            'activeTab' => $activeTab,
        ]);
    }

    private function buildTrendData(string $trendUnit, int $selectedYear, array $availableYears): array
    {
        if ($trendUnit === 'year') {
            $years = collect($availableYears)->sort()->values()->take(-6)->values();
            $rows = StockTransaction::query()
                ->selectRaw('YEAR(created_at) as period')
                ->selectRaw('SUM(quantity) as qty_out')
                ->where('type', 'out')
                ->whereIn(DB::raw('YEAR(created_at)'), $years->all())
                ->groupBy('period')
                ->pluck('qty_out', 'period');

            $labels = $years->map(fn ($year) => (string) $year)->all();
            $values = $years->map(fn ($year) => (int) ($rows[$year] ?? 0))->all();

            return [$labels, $values];
        }

        if ($trendUnit === 'week') {
            $rows = StockTransaction::query()
                ->selectRaw('WEEK(created_at, 3) as period')
                ->selectRaw('SUM(quantity) as qty_out')
                ->where('type', 'out')
                ->whereYear('created_at', $selectedYear)
                ->groupBy('period')
                ->pluck('qty_out', 'period');

            $labels = [];
            $values = [];
            for ($week = 1; $week <= 53; $week++) {
                $labels[] = 'W' . str_pad((string) $week, 2, '0', STR_PAD_LEFT);
                $values[] = (int) ($rows[$week] ?? 0);
            }

            return [$labels, $values];
        }

        $rows = StockTransaction::query()
            ->selectRaw('MONTH(created_at) as period')
            ->selectRaw('SUM(quantity) as qty_out')
            ->where('type', 'out')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('period')
            ->pluck('qty_out', 'period');

        $labels = [];
        $values = [];
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create($selectedYear, $month, 1)->translatedFormat('M');
            $values[] = (int) ($rows[$month] ?? 0);
        }

        return [$labels, $values];
    }
}
