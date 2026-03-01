<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Models\StockTransaction;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin,staff,customer'])
    ->name('dashboard');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified', 'role:admin,staff,customer'])->name('home');

Route::get('/statistics', function (Request $request) {
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
        $selectedYear = $availableYears->first();
    }

    $selectedChart = (string) $request->query('chart', 'bar');
    if (! in_array($selectedChart, ['bar', 'line', 'pie'], true)) {
        $selectedChart = 'bar';
    }

    $transactions = StockTransaction::query()
        ->whereYear('created_at', $selectedYear)
        ->get(['type', 'quantity', 'created_at']);

    $monthly = [];
    for ($monthNumber = 1; $monthNumber <= 12; $monthNumber++) {
        $month = Carbon::create($selectedYear, $monthNumber, 1);
        $key = $month->format('Y-m');
        $monthly[$key] = [
            'label' => $month->translatedFormat('M'),
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
            $monthly[$key]['in'] += (int) $transaction->quantity;
        } elseif ($transaction->type === 'out') {
            $monthly[$key]['out'] += (int) $transaction->quantity;
        }
    }

    return view('statistics.index', [
        'monthlyStockFlow' => array_values($monthly),
        'availableYears' => $availableYears,
        'selectedYear' => $selectedYear,
        'selectedChart' => $selectedChart,
    ]);
})->middleware(['auth', 'verified', 'role:admin,staff'])->name('statistics.index');

Route::get('/analytics', [AnalyticsController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin,staff'])
    ->name('analytics.index');

Route::middleware('auth')->group(function () {
    Route::get('/welcome', function () {
        return view('auth.welcome');
    })->name('auth.welcome');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('products.index');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->middleware('role:admin,staff')
        ->name('products.show');

    Route::get('/products/{product}/stock',
        [StockTransactionController::class, 'index'])
        ->middleware('role:admin,staff')
        ->name('products.stock');

    Route::post('/products/{product}/stock',
        [StockTransactionController::class, 'store'])
        ->middleware('role:admin,staff')
        ->name('products.stock.store');

    Route::get('/sales', [SalesController::class, 'index'])
        ->middleware('role:admin,staff,customer')
        ->name('sales.index');
    Route::post('/sales/checkout', [SalesController::class, 'checkout'])
        ->middleware('role:customer')
        ->name('sales.checkout');
    Route::post('/sales', [SalesController::class, 'store'])
        ->middleware('role:customer')
        ->name('sales.store');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])
        ->middleware('role:customer')
        ->name('orders.my');
    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->middleware('role:admin,staff,customer')
        ->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->middleware('role:admin,staff')
        ->name('orders.status.update');

    Route::middleware('role:admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::resource('categories', CategoryController::class);

        Route::resource('suppliers', SupplierController::class);
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('/users/{user}/send-reset-link', [UserController::class, 'sendResetLink'])
            ->name('users.send-reset-link');

        Route::get('/reports/stock', [StockReportController::class, 'index'])
            ->name('reports.stock');
        Route::get('/reports/stock/export', [StockReportController::class, 'exportCsv'])
            ->name('reports.stock.export');
        Route::get('/reports/stock/export-excel', [StockReportController::class, 'exportExcel'])
            ->name('reports.stock.export_excel');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

require __DIR__.'/auth.php';
