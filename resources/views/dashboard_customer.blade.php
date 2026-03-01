<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Customer
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <p class="text-sm font-semibold tracking-wide text-slate-600">Selamat datang</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ auth()->user()->name }}</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Ini dashboard khusus customer. Gunakan menu Marketplace untuk memilih produk dan lakukan checkout.
                </p>
                <a href="{{ route('sales.index') }}" class="mt-4 inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                    Buka Marketplace
                </a>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <article class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-xs text-slate-500">Langkah 1</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">Pilih Produk</p>
                    <p class="mt-1 text-xs text-slate-600">Klik produk untuk lihat detail dan jumlah.</p>
                </article>
                <article class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-xs text-slate-500">Langkah 2</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">Tambah Keranjang</p>
                    <p class="mt-1 text-xs text-slate-600">Simpan beberapa item sebelum lanjut.</p>
                </article>
                <article class="rounded-xl border border-slate-200 bg-white p-4">
                    <p class="text-xs text-slate-500">Langkah 3</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">Checkout</p>
                    <p class="mt-1 text-xs text-slate-600">Isi pembayaran dan alamat penerima.</p>
                </article>
            </section>
        </div>
    </div>
</x-app-layout>
