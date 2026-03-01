<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransaction::query()
            ->with(['product.category', 'product.supplier'])
            ->latest();

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from')->toString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to')->toString());
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        $transactions = $query->paginate(15)->withQueryString();

        $summaryQuery = StockTransaction::query();
        if ($request->filled('from')) {
            $summaryQuery->whereDate('created_at', '>=', $request->string('from')->toString());
        }
        if ($request->filled('to')) {
            $summaryQuery->whereDate('created_at', '<=', $request->string('to')->toString());
        }
        if ($request->filled('type')) {
            $summaryQuery->where('type', $request->string('type')->toString());
        }

        $totalIn = (clone $summaryQuery)->where('type', 'in')->sum('quantity');
        $totalOut = (clone $summaryQuery)->where('type', 'out')->sum('quantity');

        return view('reports.stock', [
            'transactions' => $transactions,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'netFlow' => $totalIn - $totalOut,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $transactions = $this->buildReportQuery($request)->get();
        $filename = 'laporan-stok-' . Carbon::now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($transactions) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel reads UTF-8 correctly.
            fwrite($handle, "\xEF\xBB\xBF");
            // Excel delimiter hint for locales that default to semicolon.
            fwrite($handle, "sep=;\r\n");

            fputcsv($handle, [
                'Tanggal',
                'Produk',
                'Kategori',
                'Supplier',
                'Tipe',
                'Jumlah',
                'Catatan',
            ], ';');

            foreach ($transactions as $transaction) {
                fputcsv($handle, [
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->product->nama_barang ?? '-',
                    $transaction->product->category->nama ?? '-',
                    $transaction->product->supplier->nama_supplier ?? '-',
                    $transaction->type,
                    $transaction->quantity,
                    $transaction->note ?? '',
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportExcel(Request $request)
    {
        $transactions = $this->buildReportQuery($request, true)->get();
        $filename = 'laporan-stok-' . Carbon::now()->format('Ymd-His') . '.xls';
        $html = view('reports.stock_excel', compact('transactions'))->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function buildReportQuery(Request $request, bool $groupByType = false)
    {
        $query = StockTransaction::query()
            ->with(['product.category', 'product.supplier'])
            ->latest();

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from')->toString());
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to')->toString());
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type')->toString());
        }

        if ($groupByType && ! $request->filled('type')) {
            $query->reorder()
                ->orderByRaw("CASE WHEN type = 'in' THEN 0 ELSE 1 END")
                ->orderByDesc('created_at');
        }

        return $query;
    }
}
