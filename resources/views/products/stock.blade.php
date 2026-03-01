<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Manajemen Stok
    </h2>
</x-slot>

<div class="py-8">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-lg border border-emerald-300/40 bg-emerald-500/10 text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500">Product Stock Console</p>
                <h3 class="text-lg font-semibold text-gray-800 mt-1">{{ $product->nama_barang }}</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Kategori: {{ $product->category->nama ?? '-' }} | Supplier: {{ $product->supplier->nama_supplier ?? '-' }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs uppercase tracking-wider text-gray-500">Stok Saat Ini</p>
                @if($product->stock == 0)
                    <span class="stock-badge stock-badge-danger inline-flex mt-2 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $product->stock }}
                    </span>
                @elseif($product->stock < 5)
                    <span class="stock-badge stock-badge-warning inline-flex mt-2 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $product->stock }}
                    </span>
                @else
                    <span class="stock-badge stock-badge-safe inline-flex mt-2 px-3 py-1 rounded-full text-sm font-bold">
                        {{ $product->stock }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h4 class="text-base font-semibold mb-4">Tambah Transaksi Stok</h4>

        <form method="POST" action="{{ route('products.stock.store', $product) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf

            <div>
                <label class="block mb-2 font-medium">Tipe Transaksi</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>Stok Masuk</option>
                    <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Stok Keluar</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium">Jumlah</label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity') }}"
                       class="w-full border rounded px-3 py-2" placeholder="Contoh: 10" required>
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium">Catatan</label>
                <input type="text" name="note" value="{{ old('note') }}"
                       class="w-full border rounded px-3 py-2" placeholder="Opsional">
            </div>

            <div class="md:col-span-3 flex flex-wrap gap-3 mt-1">
                <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                    Simpan Transaksi
                </button>
                <a href="{{ route('products.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    Kembali ke Produk
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <h4 class="text-base font-semibold">Riwayat Transaksi</h4>

            <form method="GET" action="{{ route('products.stock', $product) }}" class="flex items-center gap-2">
                <select name="type" class="border rounded px-3 py-2 text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Masuk</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar</option>
                </select>
                <button class="bg-gray-800 text-white px-3 py-2 rounded text-sm hover:bg-gray-900">Filter</button>
                @if(request('type'))
                    <a href="{{ route('products.stock', $product) }}" class="bg-gray-400 text-white px-3 py-2 rounded text-sm hover:bg-gray-500">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-3 border">Tanggal</th>
                        <th class="px-4 py-3 border">Tipe</th>
                        <th class="px-4 py-3 border">Jumlah</th>
                        <th class="px-4 py-3 border">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-4 py-3 border">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 border">
                            @if($transaction->type === 'in')
                                <span class="stock-badge stock-badge-safe px-2.5 py-1 rounded-full text-xs font-semibold">
                                    Masuk
                                </span>
                            @else
                                <span class="stock-badge stock-badge-danger px-2.5 py-1 rounded-full text-xs font-semibold">
                                    Keluar
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border font-medium">{{ $transaction->quantity }}</td>
                        <td class="px-4 py-3 border">{{ $transaction->note ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            Belum ada transaksi stok.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>

</div>
</div>
</x-app-layout>
