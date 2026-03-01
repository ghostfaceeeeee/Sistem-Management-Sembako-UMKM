<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $stockSummarySubquery = \App\Models\StockTransaction::query()
            ->select('product_id')
            ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END) as stock")
            ->groupBy('product_id');

        $query = Product::query()
            ->with(['category', 'supplier'])
            ->leftJoinSub($stockSummarySubquery, 'stock_summary', function ($join) {
                $join->on('products.id', '=', 'stock_summary.product_id');
            })
            ->select('products.*')
            ->selectRaw('COALESCE(stock_summary.stock, 0) as current_stock');

        if ($request->search) {
            $query->where('products.nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->category_id) {
            $query->where('products.category_id', $request->category_id);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = strtolower($request->get('sort_dir', 'desc'));

        $allowedSorts = [
            'created_at' => 'products.created_at',
            'nama_barang' => 'products.nama_barang',
            'harga_jual' => 'products.harga_jual',
            'stock' => 'current_stock',
        ];

        $sortColumn = $allowedSorts[$sortBy] ?? 'products.created_at';
        $sortDir = in_array($sortDir, ['asc', 'desc'], true) ? $sortDir : 'desc';

        $query->orderBy($sortColumn, $sortDir);

        $products = $query->paginate(10)->withQueryString();

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'category_id' => 'required|exists:categories,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'harga_jual.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
        ]);

        $data = $request->only([
            'nama_barang',
            'category_id',
            'harga_beli',
            'harga_jual',
            'supplier_id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.stock', $product);
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::all();
        $categories = Category::all();

        return view('products.edit', compact('product', 'suppliers', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_barang' => 'required',
            'category_id' => 'required|exists:categories,id',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|gte:harga_beli',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'harga_jual.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
        ]);

        $data = $request->only([
            'nama_barang',
            'category_id',
            'harga_beli',
            'harga_jual',
            'supplier_id',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }
}
