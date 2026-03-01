<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isCustomer()) {
            return view('dashboard_customer');
        }

        $stockSummarySubquery = StockTransaction::query()
            ->select('product_id')
            ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END) as stock")
            ->groupBy('product_id');

        $lowStockProducts = Product::query()
            ->with(['category', 'supplier'])
            ->leftJoinSub($stockSummarySubquery, 'stock_summary', function ($join) {
                $join->on('products.id', '=', 'stock_summary.product_id');
            })
            ->select('products.*')
            ->selectRaw('COALESCE(stock_summary.stock, 0) as current_stock')
            ->whereRaw('COALESCE(stock_summary.stock, 0) <= 10')
            ->orderBy('current_stock')
            ->limit(10)
            ->get();

        $startMonth = Carbon::now()->startOfMonth()->subMonths(5);
        $transactions = StockTransaction::query()
            ->where('created_at', '>=', $startMonth)
            ->get(['type', 'quantity', 'created_at']);

        $monthly = [];
        for ($i = 0; $i < 6; $i++) {
            $month = $startMonth->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $monthly[$key] = [
                'label' => $month->translatedFormat('M Y'),
                'in' => 0,
                'out' => 0,
            ];
        }

        foreach ($transactions as $transaction) {
            $key = Carbon::parse($transaction->created_at)->format('Y-m');
            if (! isset($monthly[$key])) {
                continue;
            }

            if ($transaction->type === 'in') {
                $monthly[$key]['in'] += $transaction->quantity;
            }

            if ($transaction->type === 'out') {
                $monthly[$key]['out'] += $transaction->quantity;
            }
        }

        return view('dashboard', [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalSuppliers' => Supplier::count(),
            'lowStockProducts' => $lowStockProducts,
            'lowStockCount' => $lowStockProducts->count(),
            'monthlyStockFlow' => array_values($monthly),
        ]);
    }
}
