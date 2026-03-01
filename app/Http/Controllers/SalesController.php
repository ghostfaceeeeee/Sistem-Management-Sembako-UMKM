<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $isCustomer = $request->user()->isCustomer();
        $previewMode = ! $isCustomer && $request->boolean('preview_customer');

        if (! $isCustomer && ! $previewMode) {
            $ordersQuery = Order::query()
                ->with(['user', 'items'])
                ->latest();

            if ($request->filled('status')) {
                $ordersQuery->where('status', (string) $request->status);
            }

            $orders = $ordersQuery->paginate(12)->withQueryString();

            return view('sales.manage', [
                'orders' => $orders,
                'statusOptions' => ['pending', 'paid', 'packed', 'done', 'cancelled'],
            ]);
        }

        $stockSummarySubquery = StockTransaction::query()
            ->select('product_id')
            ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END) as stock")
            ->groupBy('product_id');

        $products = Product::query()
            ->with(['category', 'supplier'])
            ->leftJoinSub($stockSummarySubquery, 'stock_summary', function ($join) {
                $join->on('products.id', '=', 'stock_summary.product_id');
            })
            ->select('products.*')
            ->selectRaw('COALESCE(stock_summary.stock, 0) as current_stock')
            ->orderBy('products.nama_barang')
            ->get();

        $sales = StockTransaction::query()
            ->with('product')
            ->where('type', 'out')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', [
            'products' => $products,
            'sales' => $sales,
            'canCheckout' => $isCustomer,
            'previewMode' => $previewMode,
            'showHistory' => false,
        ]);
    }

    public function store(Request $request)
    {
        $normalized = null;
        $cartPayload = $request->input('cart_payload');
        if (is_string($cartPayload) && trim($cartPayload) !== '') {
            $normalized = $this->normalizeCartPayload($cartPayload);
        } else {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'note' => 'nullable|string|max:255',
            ]);

            $normalized = collect([[
                'product_id' => (int) $validated['product_id'],
                'quantity' => (int) $validated['quantity'],
            ]]);
        }

        if (! $normalized || $normalized->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['cart' => 'Keranjang kosong. Tambahkan produk dulu.']);
        }

        $products = $this->getProductsForCart($normalized);

        foreach ($normalized as $item) {
            $product = $products->get($item['product_id']);
            if (! $product) {
                return back()
                    ->withInput()
                    ->withErrors(['cart' => 'Ada produk di keranjang yang tidak ditemukan.']);
            }

            if ($item['quantity'] > (int) $product->stock) {
                return back()
                    ->withInput()
                    ->withErrors(['cart' => 'Stok tidak cukup untuk produk: ' . $product->nama_barang . '.']);
            }
        }

        $note = trim((string) $request->input('note', ''));
        $paymentMethod = trim((string) $request->input('payment_method', ''));
        $shippingAddress = trim((string) $request->input('shipping_address', ''));

        $meta = [];
        if ($paymentMethod !== '') {
            $meta[] = 'Metode: '.$paymentMethod;
        }
        if ($shippingAddress !== '') {
            $meta[] = 'Alamat: '.$shippingAddress;
        }
        $metaText = implode(' | ', $meta);
        $baseNote = trim(($note !== '' ? $note : 'Checkout marketplace').($metaText !== '' ? ' | '.$metaText : ''));

        $orderCode = 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));

        $order = DB::transaction(function () use ($normalized, $products, $baseNote, $orderCode, $paymentMethod, $shippingAddress) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_code' => $orderCode,
                'status' => 'paid',
                'payment_method' => $paymentMethod !== '' ? $paymentMethod : null,
                'shipping_address' => $shippingAddress !== '' ? $shippingAddress : null,
                'note' => $baseNote !== '' ? $baseNote : null,
                'total_amount' => 0,
            ]);

            $grandTotal = 0;
            foreach ($normalized as $item) {
                $product = $products->get($item['product_id']);
                $quantity = (int) $item['quantity'];
                $price = (int) $product->harga_jual;
                $subtotal = $price * $quantity;
                $grandTotal += $subtotal;

                $order->items()->create([
                    'product_id' => (int) $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $product->stockTransactions()->create([
                    'type' => 'out',
                    'quantity' => $quantity,
                    'note' => trim('Order '.$order->order_code.' | '.$baseNote),
                ]);
            }

            $order->update([
                'total_amount' => $grandTotal,
            ]);

            return $order;
        });

        return redirect()
            ->route('sales.index')
            ->with('success', 'Checkout berhasil diproses. Kode pesanan: '.$order->order_code);
    }

    public function checkout(Request $request)
    {
        $cartPayload = (string) $request->input('cart_payload', '');
        $normalized = $this->normalizeCartPayload($cartPayload);

        if (! $normalized || $normalized->isEmpty()) {
            return redirect()
                ->route('sales.index')
                ->withErrors(['cart' => 'Keranjang kosong. Tambahkan produk dulu.']);
        }

        $products = $this->getProductsForCart($normalized);
        $lines = $normalized->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);
            if (! $product) {
                return null;
            }

            $price = (int) $product->harga_jual;
            $qty = (int) $item['quantity'];

            return [
                'product_id' => (int) $product->id,
                'name' => $product->nama_barang,
                'price' => $price,
                'quantity' => $qty,
                'subtotal' => $price * $qty,
                'current_stock' => (int) $product->stock,
            ];
        })->filter()->values();

        if ($lines->isEmpty()) {
            return redirect()
                ->route('sales.index')
                ->withErrors(['cart' => 'Item keranjang tidak valid.']);
        }

        return view('sales.checkout', [
            'cartPayload' => json_encode($normalized->values()->all(), JSON_UNESCAPED_UNICODE),
            'lines' => $lines,
            'grandTotal' => (int) $lines->sum('subtotal'),
            'note' => (string) $request->input('note', ''),
        ]);
    }

    private function normalizeCartPayload(?string $cartPayload)
    {
        if (! is_string($cartPayload) || trim($cartPayload) === '') {
            return null;
        }

        $items = json_decode($cartPayload, true);
        if (! is_array($items) || empty($items)) {
            return null;
        }

        return collect($items)
            ->map(function ($item) {
                return [
                    'product_id' => (int) ($item['product_id'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? 0),
                ];
            })
            ->filter(fn ($item) => $item['product_id'] > 0 && $item['quantity'] > 0)
            ->groupBy('product_id')
            ->map(function ($group) {
                return [
                    'product_id' => (int) $group->first()['product_id'],
                    'quantity' => (int) $group->sum('quantity'),
                ];
            })
            ->values();
    }

    private function getProductsForCart($normalized)
    {
        return Product::query()
            ->whereIn('id', $normalized->pluck('product_id')->all())
            ->get()
            ->keyBy('id');
    }
}
