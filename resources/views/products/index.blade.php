<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Daftar Produk
    </h2>
</x-slot>

<div class="py-8">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-lg border border-emerald-300/40 bg-emerald-500/10 text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6 relative z-30 overflow-visible">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500">Inventory Overview</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">
                    Total Produk:
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-300/25 border border-yellow-300/40 text-gray-900 ml-1">
                        {{ $products->total() }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('products.create') }}"
                       class="inline-flex items-center px-4 py-2 rounded-md bg-gray-800 text-white text-sm font-semibold hover:bg-gray-900">
                        + Tambah Produk
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                @click.outside="open = false"
                                class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200">
                            ⋮
                        </button>

                        <div x-show="open"
                             x-transition
                             class="absolute right-0 mt-2 w-52 z-[80]">
                            <div class="dropdown-panel rounded-md overflow-hidden">
                                <a href="{{ route('categories.index') }}" class="dropdown-item block px-4 py-2 text-sm">
                                    Kelola Kategori
                                </a>
                                <a href="{{ route('suppliers.index') }}" class="dropdown-item block px-4 py-2 text-sm">
                                    Kelola Supplier
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 relative z-10">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama produk..."
                   class="xl:col-span-2 border border-gray-300 rounded-md px-3 py-2">

            <select name="category_id" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->nama }}
                    </option>
                @endforeach
            </select>

            <select name="sort_by" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Urut: Tanggal</option>
                <option value="nama_barang" {{ request('sort_by') == 'nama_barang' ? 'selected' : '' }}>Urut: Nama Produk</option>
                <option value="harga_jual" {{ request('sort_by') == 'harga_jual' ? 'selected' : '' }}>Urut: Harga Jual</option>
                <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Urut: Stok</option>
            </select>

            <select name="sort_dir" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="desc" {{ request('sort_dir', 'desc') == 'desc' ? 'selected' : '' }}>Terbesar/Terbaru</option>
                <option value="asc" {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>Terkecil/Terlama</option>
            </select>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full md:w-auto bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900">
                    Filter
                </button>

                @if(request('search') || request('category_id') || request('sort_by') || request('sort_dir'))
                    <a href="{{ route('products.index') }}"
                       class="w-full md:w-auto text-center bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-3 border">#</th>
                        <th class="px-4 py-3 border">Gambar</th>
                        <th class="px-4 py-3 border">Nama Barang</th>
                        <th class="px-4 py-3 border">Kategori</th>
                        <th class="px-4 py-3 border">Harga Jual</th>
                        <th class="px-4 py-3 border">Stok</th>
                        <th class="px-4 py-3 border">Supplier</th>
                        <th class="px-4 py-3 border text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($products as $index => $product)
                    <tr>
                        <td class="px-4 py-3 border">{{ $products->firstItem() + $index }}</td>
                        <td class="px-4 py-3 border">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->nama_barang }}"
                                     class="h-12 w-12 rounded-lg object-cover border border-slate-300">
                            @else
                                <div class="h-12 w-12 rounded-lg border border-slate-300 bg-slate-100 flex items-center justify-center text-[10px] text-slate-500">
                                    No Img
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 border font-medium">{{ $product->nama_barang }}</td>
                        <td class="px-4 py-3 border">{{ $product->category->nama ?? '-' }}</td>
                        <td class="px-4 py-3 border">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 border">
                            @if($product->stock == 0)
                                <span class="stock-badge stock-badge-danger px-2.5 py-1 rounded-full text-xs font-bold">
                                    {{ $product->stock }}
                                </span>
                            @elseif($product->stock < 5)
                                <span class="stock-badge stock-badge-warning px-2.5 py-1 rounded-full text-xs font-bold">
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="stock-badge stock-badge-safe px-2.5 py-1 rounded-full text-xs font-bold">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border">{{ $product->supplier->nama_supplier ?? '-' }}</td>
                        <td class="px-4 py-3 border text-center space-x-2">
                            <a href="{{ route('products.stock', $product->id) }}"
                               class="inline-block bg-slate-700 text-white px-3 py-1 rounded text-xs hover:bg-slate-800">
                                Stok
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="inline-block bg-yellow-400 text-black px-3 py-1 rounded text-xs hover:bg-yellow-500">
                                    Edit
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500">
                            Belum ada produk tersedia.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>

</div>
</div>
</x-app-layout>
