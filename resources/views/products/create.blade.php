<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl leading-tight">
        Tambah Produk
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl p-6 md:p-8">
            <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="nama_barang" class="mb-2 block text-sm font-semibold">Nama Barang</label>
                        <input
                            id="nama_barang"
                            type="text"
                            name="nama_barang"
                            value="{{ old('nama_barang') }}"
                            class="w-full rounded-xl border px-3 py-2.5"
                            required
                        >
                        @error('nama_barang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="image" class="mb-2 block text-sm font-semibold">Gambar Produk</label>
                        <input
                            id="image"
                            type="file"
                            name="image"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="w-full rounded-xl border px-3 py-2.5"
                        >
                        <p class="mt-1 text-xs text-slate-500">Opsional. Format: JPG, PNG, WEBP. Maks 2MB.</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="mb-2 block text-sm font-semibold">Kategori</label>
                        <select
                            id="category_id"
                            name="category_id"
                            class="w-full rounded-xl border px-3 py-2.5"
                            required
                        >
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_id" class="mb-2 block text-sm font-semibold">Supplier</label>
                        <select
                            id="supplier_id"
                            name="supplier_id"
                            class="w-full rounded-xl border px-3 py-2.5"
                            required
                        >
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harga_beli" class="mb-2 block text-sm font-semibold">Harga Beli</label>
                        <input
                            id="harga_beli"
                            type="number"
                            min="0"
                            name="harga_beli"
                            value="{{ old('harga_beli') }}"
                            class="w-full rounded-xl border px-3 py-2.5"
                            required
                        >
                        @error('harga_beli')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="harga_jual" class="mb-2 block text-sm font-semibold">Harga Jual</label>
                        <input
                            id="harga_jual"
                            type="number"
                            min="0"
                            name="harga_jual"
                            value="{{ old('harga_jual') }}"
                            class="w-full rounded-xl border px-3 py-2.5"
                            required
                        >
                        @error('harga_jual')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-7 flex flex-wrap gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-xl bg-black px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 dark:bg-yellow-300 dark:text-black"
                    >
                        Simpan
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center rounded-xl border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-600 dark:text-slate-100 dark:hover:bg-slate-800"
                    >
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
