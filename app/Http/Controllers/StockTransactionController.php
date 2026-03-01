<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function index(Product $product)
    {
        $query = $product->stockTransactions()->latest();

        if (request()->filled('type')) {
            $query->where('type', request()->string('type')->toString());
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('products.stock', compact('product', 'transactions'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        if ($validated['type'] === 'out' && $validated['quantity'] > $product->stock) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['quantity' => 'Stok tidak mencukupi untuk transaksi keluar.']);
        }

        $product->stockTransactions()->create($validated);

        return redirect()
            ->route('products.stock', $product)
            ->with('success', 'Transaksi stok berhasil ditambahkan.');
    }
}
