<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Laporan Transaksi Stok
    </h2>
</x-slot>

<div class="py-8">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500">Stock Analytics</p>
                <h3 class="text-lg font-semibold text-gray-800 mt-1">Filter & Export Laporan</h3>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.stock.export_excel', request()->only(['from', 'to', 'type'])) }}"
                   class="inline-flex items-center px-4 py-2 rounded-md bg-gray-800 text-white text-sm font-semibold hover:bg-gray-900">
                    Export Excel
                </a>
                <a href="{{ route('reports.stock.export', request()->only(['from', 'to', 'type'])) }}"
                   class="inline-flex items-center px-4 py-2 rounded-md bg-gray-400 text-white text-sm font-semibold hover:bg-gray-500">
                    Export CSV
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('reports.stock') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block mb-2 text-sm font-medium">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">Tipe</label>
                <select name="type" class="w-full border rounded px-3 py-2">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Masuk</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>

            <div class="flex gap-2 md:col-span-2">
                <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                    Filter
                </button>
                <a href="{{ route('reports.stock') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow rounded-lg p-5 border-l-4 border-green-500/80">
            <p class="text-sm text-gray-500">Total Stok Masuk</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalIn }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5 border-l-4 border-red-500/80">
            <p class="text-sm text-gray-500">Total Stok Keluar</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalOut }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-5 border-l-4 border-yellow-400/90">
            <p class="text-sm text-gray-500">Net Flow</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $netFlow }}</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-3 border">Tanggal</th>
                        <th class="px-4 py-3 border">Produk</th>
                        <th class="px-4 py-3 border">Kategori</th>
                        <th class="px-4 py-3 border">Supplier</th>
                        <th class="px-4 py-3 border">Tipe</th>
                        <th class="px-4 py-3 border">Jumlah</th>
                        <th class="px-4 py-3 border">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td class="px-4 py-3 border">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 border font-medium">{{ $transaction->product->nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3 border">{{ $transaction->product->category->nama ?? '-' }}</td>
                            <td class="px-4 py-3 border">{{ $transaction->product->supplier->nama_supplier ?? '-' }}</td>
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
                            <td class="px-4 py-3 border font-semibold">{{ $transaction->quantity }}</td>
                            <td class="px-4 py-3 border">{{ $transaction->note ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Tidak ada data transaksi untuk filter ini.
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
