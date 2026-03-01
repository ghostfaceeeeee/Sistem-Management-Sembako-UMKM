<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Inventory
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-cyan-400/80">
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-orange-300/80">
                    <p class="text-sm text-gray-500">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCategories }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-5 border-l-4 border-cyan-300/70">
                    <p class="text-sm text-gray-500">Total Supplier</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalSuppliers }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800">Notifikasi Stok Rendah</h3>
                        <p class="text-sm text-gray-500">Produk dengan stok kurang atau sama dengan 10</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-black text-sm">
                        Lihat Produk
                    </a>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-md">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-3 border">Produk</th>
                                <th class="px-4 py-3 border">Kategori</th>
                                <th class="px-4 py-3 border">Supplier</th>
                                <th class="px-4 py-3 border">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td class="px-4 py-3 border">{{ $product->nama_barang }}</td>
                                    <td class="px-4 py-3 border">{{ $product->category->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 border">{{ $product->supplier->nama_supplier ?? '-' }}</td>
                                    <td class="px-4 py-3 border">
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                            {{ (int) $product->current_stock }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-5 border text-center text-gray-500">
                                        Tidak ada produk dengan stok rendah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800">Arus Stok 6 Bulan Terakhir</h3>
                        <p class="text-sm text-gray-500">Ringkasan stok masuk dan keluar per bulan</p>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('reports.stock') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            Buka Laporan
                        </a>
                    @endif
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-md">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-3 border">Bulan</th>
                                <th class="px-4 py-3 border">Stok Masuk</th>
                                <th class="px-4 py-3 border">Stok Keluar</th>
                                <th class="px-4 py-3 border">Net</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyStockFlow as $month)
                                <tr>
                                    <td class="px-4 py-3 border">{{ $month['label'] }}</td>
                                    <td class="px-4 py-3 border text-emerald-700 font-medium">{{ $month['in'] }}</td>
                                    <td class="px-4 py-3 border text-rose-700 font-medium">{{ $month['out'] }}</td>
                                    <td class="px-4 py-3 border font-semibold">{{ $month['in'] - $month['out'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
