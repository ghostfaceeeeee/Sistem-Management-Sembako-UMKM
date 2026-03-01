<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Home
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(auth()->user()->isCustomer())
                <section class="rounded-3xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 md:p-10 dark:bg-slate-950/85 dark:border-slate-600 overflow-hidden relative">
                    <div class="absolute -top-16 -right-16 h-56 w-56 rounded-full bg-yellow-300/30 blur-3xl"></div>
                    <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-slate-300/25 blur-3xl"></div>

                    <p class="relative text-sm font-semibold tracking-wide text-black/80 dark:text-white/85">UMKM Sembako Marketplace</p>
                    <h3 class="relative mt-2 text-3xl md:text-5xl font-bold text-black dark:text-white max-w-4xl leading-tight">
                        Belanja kebutuhan sembako lebih cepat dan simpel.
                    </h3>
                    <p class="relative mt-4 text-black/85 dark:text-white/90 max-w-3xl leading-relaxed text-base md:text-lg">
                        Pilih produk di marketplace, atur jumlah, lalu lanjutkan checkout dengan detail pembayaran dan alamat penerima.
                    </p>
                    <a href="{{ route('sales.index') }}" class="relative mt-6 inline-flex items-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                        Mulai Belanja
                    </a>
                </section>

                <section class="mt-6 grid gap-6 md:grid-cols-3">
                    <article class="rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                        <h4 class="text-lg font-bold text-black dark:text-white">1. Pilih Produk</h4>
                        <p class="mt-2 text-sm text-black/85 dark:text-white/90 leading-relaxed">
                            Buka menu Marketplace, pilih produk yang kamu butuhkan, lalu atur jumlah item.
                        </p>
                    </article>
                    <article class="rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                        <h4 class="text-lg font-bold text-black dark:text-white">2. Tambah Keranjang</h4>
                        <p class="mt-2 text-sm text-black/85 dark:text-white/90 leading-relaxed">
                            Simpan beberapa produk ke keranjang, cek subtotal, lalu lanjutkan ke pembayaran.
                        </p>
                    </article>
                    <article class="rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                        <h4 class="text-lg font-bold text-black dark:text-white">3. Checkout</h4>
                        <p class="mt-2 text-sm text-black/85 dark:text-white/90 leading-relaxed">
                            Isi metode pembayaran dan alamat penerima pada halaman checkout sebelum konfirmasi akhir.
                        </p>
                    </article>
                </section>
            @else
                <section class="rounded-3xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 md:p-10 dark:bg-slate-950/85 dark:border-slate-600 overflow-hidden relative">
                    <div class="absolute -top-16 -right-16 h-56 w-56 rounded-full bg-yellow-300/30 blur-3xl"></div>
                    <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-slate-300/25 blur-3xl"></div>

                    <p class="relative text-sm font-semibold tracking-wide text-black/80 dark:text-white/85">UMKM Sembako</p>
                    <h3 class="relative mt-2 text-3xl md:text-5xl font-bold text-black dark:text-white max-w-4xl leading-tight">
                        Platform operasional toko sembako yang rapi, terpusat, dan siap scale.
                    </h3>
                    <p class="relative mt-4 text-black/85 dark:text-white/90 max-w-3xl leading-relaxed text-base md:text-lg">
                        Halaman ini fokus sebagai profil singkat aplikasi. Semua angka statistik dipisahkan ke menu Statistik
                        agar Home tetap bersih dan nyaman dibaca.
                    </p>
                </section>

                <section class="mt-6 grid gap-6 md:grid-cols-6">
                    <article class="md:col-span-3 rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                        <h4 class="text-lg font-bold text-black dark:text-white">Data Master</h4>
                        <p class="mt-2 text-sm text-black/85 dark:text-white/90 leading-relaxed">
                            Kelola Produk, Kategori, dan Supplier sebagai pondasi data inventory yang konsisten.
                        </p>
                    </article>

                    <article class="md:col-span-3 rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                        <h4 class="text-lg font-bold text-black dark:text-white">Pergerakan Stok</h4>
                        <p class="mt-2 text-sm text-black/85 dark:text-white/90 leading-relaxed">
                            Stok dihitung berdasarkan transaksi masuk dan keluar, bukan input manual angka stok.
                        </p>
                    </article>

                    <article class="md:col-span-4 rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                        <h4 class="text-lg font-bold text-black dark:text-white">Kontrol Akses</h4>
                        <p class="mt-2 text-sm text-black/85 dark:text-white/90 leading-relaxed">
                            Role admin dan staff membantu membatasi akses sesuai tanggung jawab operasional.
                        </p>
                    </article>

                    <article class="md:col-span-2 rounded-2xl border border-yellow-400/70 bg-slate-900/92 backdrop-blur-md p-6 dark:bg-slate-900/92 dark:border-yellow-300/60">
                        <h4 class="text-lg font-bold text-yellow-300">Navigasi Cepat</h4>
                        <p class="mt-2 text-sm text-black leading-relaxed">
                            Untuk analisis angka, buka menu <span class="font-semibold">Statistik</span> di navbar.
                        </p>
                    </article>
                </section>

                <section class="mt-6 rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-md p-6 dark:bg-slate-950/80 dark:border-slate-600">
                    <h4 class="text-lg font-bold text-black dark:text-white">Alur Penggunaan</h4>
                    <div class="mt-4 grid gap-3 md:grid-cols-4">
                        <div class="rounded-xl border border-slate-200/80 bg-white p-4 dark:border-slate-600 dark:bg-slate-900/90">
                            <p class="text-xs text-black/70 dark:text-white/75">Langkah 1</p>
                            <p class="mt-1 text-sm font-semibold text-black dark:text-white">Kelola master data</p>
                        </div>
                        <div class="rounded-xl border border-slate-200/80 bg-white p-4 dark:border-slate-600 dark:bg-slate-900/90">
                            <p class="text-xs text-black/70 dark:text-white/75">Langkah 2</p>
                            <p class="mt-1 text-sm font-semibold text-black dark:text-white">Catat stok masuk</p>
                        </div>
                        <div class="rounded-xl border border-slate-200/80 bg-white p-4 dark:border-slate-600 dark:bg-slate-900/90">
                            <p class="text-xs text-black/70 dark:text-white/75">Langkah 3</p>
                            <p class="mt-1 text-sm font-semibold text-black dark:text-white">Catat stok keluar</p>
                        </div>
                        <div class="rounded-xl border border-slate-200/80 bg-white p-4 dark:border-slate-600 dark:bg-slate-900/90">
                            <p class="text-xs text-black/70 dark:text-white/75">Langkah 4</p>
                            <p class="mt-1 text-sm font-semibold text-black dark:text-white">Pantau di Statistik</p>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
