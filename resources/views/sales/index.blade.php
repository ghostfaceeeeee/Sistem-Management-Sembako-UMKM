<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Marketplace
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $canCheckout = $canCheckout ?? auth()->user()->isCustomer();
                $previewMode = $previewMode ?? false;
                $showHistory = $showHistory ?? (! auth()->user()->isCustomer());
            @endphp

            @if(session('success'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($previewMode)
                <div class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-amber-800">
                    Mode preview customer aktif. Aksi checkout dinonaktifkan.
                    <a href="{{ route('sales.index') }}" class="ml-2 underline font-semibold">Kembali ke Manage</a>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-red-700">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Katalog Marketplace</h3>
                        <p class="mt-1 text-sm text-slate-600">Klik produk untuk lihat detail, lalu tambah ke keranjang atau checkout langsung.</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="inline-flex rounded-xl border border-slate-300 bg-slate-50 p-1">
                            <button type="button" id="view-grid-btn"
                                class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700">
                                Grid
                            </button>
                            <button type="button" id="view-tiles-btn"
                                class="rounded-lg px-3 py-1.5 text-sm font-semibold text-slate-700">
                                Tiles
                            </button>
                        </div>

                        <button type="button" id="open_checkout_btn"
                            class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90 {{ $canCheckout ? '' : 'opacity-60 cursor-not-allowed' }}"
                            {{ $canCheckout ? '' : 'disabled' }}>
                            {{ $canCheckout ? 'Keranjang' : 'Preview' }}
                            <span id="top_cart_count" class="rounded-full bg-white/20 px-2 py-0.5 text-xs">0</span>
                        </button>
                    </div>
                </div>

                <div id="market-grid" class="mt-5 grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($products as $product)
                        @php
                            $imgUrl = $product->image ? asset('storage/' . $product->image) : '';
                        @endphp
                        <button type="button"
                                class="market-card text-left rounded-xl border border-slate-200 bg-white p-2 hover:border-yellow-400 hover:shadow-sm"
                                data-product-id="{{ $product->id }}"
                                data-stock="{{ (int) $product->current_stock }}"
                                data-price="{{ (int) $product->harga_jual }}"
                                data-name="{{ $product->nama_barang }}"
                                data-category="{{ $product->category->nama ?? '-' }}"
                                data-supplier="{{ $product->supplier->nama_supplier ?? '-' }}"
                                data-image="{{ $imgUrl }}">
                            @if($product->image)
                                <img src="{{ $imgUrl }}"
                                     alt="{{ $product->nama_barang }}"
                                     class="aspect-square w-full rounded-lg object-cover">
                            @else
                                <div class="aspect-square w-full rounded-lg bg-slate-100 flex items-center justify-center text-xs text-slate-500">
                                    No Image
                                </div>
                            @endif
                            <p class="mt-2 text-sm font-semibold text-slate-900 line-clamp-1">{{ $product->nama_barang }}</p>
                            <p class="text-xs text-slate-500">Stok: {{ (int) $product->current_stock }}</p>
                            <p class="text-xs font-bold text-slate-800">Rp {{ number_format((int) $product->harga_jual, 0, ',', '.') }}</p>
                        </button>
                    @endforeach
                </div>

                <div id="market-tiles" class="mt-5 hidden space-y-2">
                    @foreach($products as $product)
                        @php
                            $imgUrl = $product->image ? asset('storage/' . $product->image) : '';
                        @endphp
                        <button type="button"
                                class="market-card w-full text-left rounded-xl border border-slate-200 bg-white p-3 hover:border-yellow-400 hover:shadow-sm"
                                data-product-id="{{ $product->id }}"
                                data-stock="{{ (int) $product->current_stock }}"
                                data-price="{{ (int) $product->harga_jual }}"
                                data-name="{{ $product->nama_barang }}"
                                data-category="{{ $product->category->nama ?? '-' }}"
                                data-supplier="{{ $product->supplier->nama_supplier ?? '-' }}"
                                data-image="{{ $imgUrl }}">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ $imgUrl }}"
                                         alt="{{ $product->nama_barang }}"
                                         class="h-14 w-14 rounded-lg object-cover aspect-square">
                                @else
                                    <div class="h-14 w-14 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] text-slate-500 aspect-square">
                                        No Image
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $product->nama_barang }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $product->category->nama ?? '-' }} | Stok: {{ (int) $product->current_stock }}
                                    </p>
                                </div>
                                <p class="text-sm font-bold text-slate-900">Rp {{ number_format((int) $product->harga_jual, 0, ',', '.') }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </section>

            @if($showHistory)
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <h3 class="text-lg font-bold text-slate-900">Riwayat Checkout Marketplace</h3>
                        <form method="GET" action="{{ route('sales.index') }}" class="flex items-center gap-2">
                            <select name="product_id" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                                <option value="">Semua Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                                        {{ $product->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white">Filter</button>
                            @if(request()->filled('product_id'))
                                <a href="{{ route('sales.index') }}" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-semibold text-slate-800">Reset</a>
                            @endif
                        </form>
                    </div>

                    <div class="mt-4 overflow-x-auto rounded-xl bg-white p-2">
                        <table class="min-w-full text-sm text-black">
                            <thead>
                                <tr class="text-left">
                                    <th class="py-2 px-3">Tanggal</th>
                                    <th class="py-2 px-3">Produk</th>
                                    <th class="py-2 px-3">Qty</th>
                                    <th class="py-2 px-3">Harga Jual</th>
                                    <th class="py-2 px-3">Total</th>
                                    <th class="py-2 px-3">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    @php
                                        $hargaJual = (int) ($sale->product->harga_jual ?? 0);
                                        $total = (int) $sale->quantity * $hargaJual;
                                    @endphp
                                    <tr class="border-t border-slate-200/70">
                                        <td class="py-2 px-3">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-2 px-3 font-semibold">{{ $sale->product->nama_barang ?? '-' }}</td>
                                        <td class="py-2 px-3">{{ (int) $sale->quantity }}</td>
                                        <td class="py-2 px-3">Rp {{ number_format($hargaJual, 0, ',', '.') }}</td>
                                        <td class="py-2 px-3">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                        <td class="py-2 px-3">{{ $sale->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-5 text-center text-slate-600">Belum ada transaksi penjualan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sales->links() }}
                    </div>
                </section>
            @endif
        </div>
    </div>

    <div id="product_modal_overlay" class="fixed inset-0 z-50 hidden bg-slate-900/50 p-4">
        <div class="mx-auto mt-10 max-w-2xl rounded-2xl bg-white p-5 shadow-2xl">
            <div class="flex items-start justify-between gap-3">
                <h4 class="text-base font-bold text-slate-900">Detail Produk</h4>
                <button id="close_product_modal" type="button" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700">Tutup</button>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <img id="modal_image" src="" alt="" class="aspect-square w-full rounded-xl object-cover bg-slate-100 hidden">
                    <div id="modal_image_placeholder" class="aspect-square w-full rounded-xl bg-slate-100 flex items-center justify-center text-sm text-slate-500">
                        No Image
                    </div>
                </div>

                <div>
                    <p id="modal_name" class="text-lg font-bold text-slate-900">-</p>
                    <p id="modal_category" class="mt-1 text-sm text-slate-600">Kategori: -</p>
                    <p id="modal_supplier" class="text-sm text-slate-600">Supplier: -</p>
                    <p id="modal_stock" class="text-sm text-slate-700 font-semibold">Stok: -</p>
                    <p id="modal_price" class="mt-1 text-base font-bold text-slate-900">Rp 0</p>

                    <div class="mt-4">
                        <label class="mb-1 block text-xs font-semibold text-slate-700">Jumlah</label>
                        <div class="flex items-center gap-2">
                            <button id="modal_qty_minus" type="button" class="h-9 w-9 rounded-lg border border-slate-300 bg-white text-slate-700">-</button>
                            <input id="modal_qty" type="number" min="1" value="1"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-900 text-center">
                            <button id="modal_qty_plus" type="button" class="h-9 w-9 rounded-lg border border-slate-300 bg-white text-slate-700">+</button>
                        </div>
                        <p id="modal_hint" class="mt-2 text-xs text-slate-600">Pilih jumlah lalu aksi.</p>
                    </div>

                    @if($canCheckout)
                        <div class="mt-4 flex items-center gap-2">
                            <button id="modal_add_cart" type="button"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-100">
                                Tambah ke Keranjang
                            </button>
                            <button id="modal_checkout_now" type="button"
                                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Checkout Sekarang
                            </button>
                        </div>
                    @else
                        <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
                            Preview mode: aksi checkout nonaktif untuk admin/staff.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="checkout_overlay" class="fixed inset-0 z-50 hidden bg-slate-900/40"></div>
    <aside id="checkout_drawer" class="fixed right-0 top-0 z-50 h-full w-full max-w-md translate-x-full bg-white shadow-2xl transition-transform duration-300">
        <form method="POST" action="{{ route('sales.checkout') }}" class="flex h-full flex-col">
            @csrf
            <input type="hidden" name="cart_payload" id="cart_payload" value="">

            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <div>
                    <p class="text-sm font-bold text-slate-900">Checkout</p>
                    <p id="drawer_cart_count" class="text-xs text-slate-500">0 item</p>
                </div>
                <button id="close_checkout_btn" type="button" class="rounded-lg border border-slate-300 px-2 py-1 text-xs text-slate-700">Tutup</button>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <div id="drawer_cart_empty" class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
                    Keranjang masih kosong.
                </div>
                <div id="drawer_cart_items" class="space-y-2"></div>
            </div>

            <div class="border-t border-slate-200 p-4 space-y-3">
                <div>
                    <p class="mb-1 text-xs font-semibold text-slate-700">Subtotal</p>
                    <div id="checkout_total" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-bold text-slate-900">
                        Rp 0
                    </div>
                </div>

                <div>
                    <label for="note" class="mb-1 block text-xs font-semibold text-slate-700">Catatan</label>
                    <input id="note" type="text" name="note" value="{{ old('note') }}"
                        placeholder="Opsional"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900">
                </div>

                <button type="submit" id="checkout_submit"
                    class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-50"
                    disabled>
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
    </aside>

    <script>
        (function () {
            const canCheckout = @json($canCheckout);
            const marketCards = document.querySelectorAll('.market-card');
            const viewGridBtn = document.getElementById('view-grid-btn');
            const viewTilesBtn = document.getElementById('view-tiles-btn');
            const marketGrid = document.getElementById('market-grid');
            const marketTiles = document.getElementById('market-tiles');

            const productModalOverlay = document.getElementById('product_modal_overlay');
            const closeProductModal = document.getElementById('close_product_modal');
            const modalImage = document.getElementById('modal_image');
            const modalImagePlaceholder = document.getElementById('modal_image_placeholder');
            const modalName = document.getElementById('modal_name');
            const modalCategory = document.getElementById('modal_category');
            const modalSupplier = document.getElementById('modal_supplier');
            const modalStock = document.getElementById('modal_stock');
            const modalPrice = document.getElementById('modal_price');
            const modalQty = document.getElementById('modal_qty');
            const modalQtyMinus = document.getElementById('modal_qty_minus');
            const modalQtyPlus = document.getElementById('modal_qty_plus');
            const modalHint = document.getElementById('modal_hint');
            const modalAddCart = document.getElementById('modal_add_cart');
            const modalCheckoutNow = document.getElementById('modal_checkout_now');

            const openCheckoutBtn = document.getElementById('open_checkout_btn');
            const topCartCount = document.getElementById('top_cart_count');
            const checkoutOverlay = document.getElementById('checkout_overlay');
            const checkoutDrawer = document.getElementById('checkout_drawer');
            const closeCheckoutBtn = document.getElementById('close_checkout_btn');
            const drawerCartItems = document.getElementById('drawer_cart_items');
            const drawerCartEmpty = document.getElementById('drawer_cart_empty');
            const drawerCartCount = document.getElementById('drawer_cart_count');
            const checkoutTotal = document.getElementById('checkout_total');
            const checkoutSubmit = document.getElementById('checkout_submit');
            const cartPayload = document.getElementById('cart_payload');

            const cart = {};
            let selectedProduct = null;

            const formatRupiah = function (value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            };

            const escapeHtml = function (str) {
                return String(str ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            };

            const getModalQty = function () {
                return Math.max(1, Number(modalQty.value || 1));
            };

            const openCheckoutDrawer = function () {
                checkoutOverlay.classList.remove('hidden');
                checkoutDrawer.classList.remove('translate-x-full');
            };

            const closeCheckoutDrawer = function () {
                checkoutOverlay.classList.add('hidden');
                checkoutDrawer.classList.add('translate-x-full');
            };

            const closeProductDetail = function () {
                productModalOverlay.classList.add('hidden');
            };

            const renderCart = function () {
                const entries = Object.values(cart);
                const totalItem = entries.length;
                const totalQty = entries.reduce((sum, item) => sum + item.quantity, 0);
                const totalPrice = entries.reduce((sum, item) => sum + (item.quantity * item.price), 0);

                topCartCount.textContent = String(totalQty);
                drawerCartCount.textContent = totalItem + ' item';
                drawerCartItems.innerHTML = '';

                if (entries.length === 0) {
                    drawerCartEmpty.classList.remove('hidden');
                    checkoutSubmit.disabled = true;
                    cartPayload.value = '';
                    checkoutTotal.textContent = formatRupiah(0);
                    return;
                }

                drawerCartEmpty.classList.add('hidden');
                checkoutSubmit.disabled = false;
                checkoutTotal.textContent = formatRupiah(totalPrice);

                entries.forEach(function (item) {
                    const row = document.createElement('div');
                    row.className = 'rounded-xl border border-slate-200 p-3';
                    row.innerHTML = `
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">${escapeHtml(item.name)}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Qty: ${item.quantity} x ${formatRupiah(item.price)}</p>
                                <p class="mt-1 text-xs font-bold text-slate-800">${formatRupiah(item.quantity * item.price)}</p>
                            </div>
                            <button type="button" class="remove-cart rounded-lg border border-red-200 px-2 py-1 text-[11px] font-semibold text-red-600" data-id="${item.product_id}">
                                Hapus
                            </button>
                        </div>
                    `;
                    drawerCartItems.appendChild(row);
                });

                drawerCartItems.querySelectorAll('.remove-cart').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        delete cart[this.dataset.id];
                        renderCart();
                    });
                });

                cartPayload.value = JSON.stringify(entries.map(function (item) {
                    return {
                        product_id: item.product_id,
                        quantity: item.quantity,
                    };
                }));
            };

            const openProductDetail = function (product) {
                selectedProduct = product;
                modalName.textContent = product.name;
                modalCategory.textContent = 'Kategori: ' + product.category;
                modalSupplier.textContent = 'Supplier: ' + product.supplier;
                modalStock.textContent = 'Stok: ' + product.stock;
                modalPrice.textContent = formatRupiah(product.price);
                modalQty.value = '1';
                modalHint.textContent = 'Pilih jumlah lalu aksi.';

                if (product.image) {
                    modalImage.src = product.image;
                    modalImage.classList.remove('hidden');
                    modalImagePlaceholder.classList.add('hidden');
                } else {
                    modalImage.src = '';
                    modalImage.classList.add('hidden');
                    modalImagePlaceholder.classList.remove('hidden');
                }

                productModalOverlay.classList.remove('hidden');
            };

            marketCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    const product = {
                        id: Number(this.dataset.productId || 0),
                        name: this.dataset.name || 'Produk',
                        category: this.dataset.category || '-',
                        supplier: this.dataset.supplier || '-',
                        stock: Number(this.dataset.stock || 0),
                        price: Number(this.dataset.price || 0),
                        image: this.dataset.image || '',
                    };
                    openProductDetail(product);
                });
            });

            modalQtyMinus?.addEventListener('click', function () {
                modalQty.value = String(Math.max(1, getModalQty() - 1));
            });

            modalQtyPlus?.addEventListener('click', function () {
                modalQty.value = String(Math.max(1, getModalQty() + 1));
            });

            modalAddCart?.addEventListener('click', function () {
                if (!canCheckout) {
                    modalHint.textContent = 'Mode preview, checkout dinonaktifkan.';
                    return;
                }
                if (!selectedProduct) {
                    return;
                }

                const qty = getModalQty();
                const existing = Number(cart[selectedProduct.id]?.quantity || 0);
                const nextQty = existing + qty;

                if (nextQty > selectedProduct.stock) {
                    modalHint.textContent = 'Qty melebihi stok tersedia.';
                    return;
                }

                cart[selectedProduct.id] = {
                    product_id: selectedProduct.id,
                    name: selectedProduct.name,
                    price: selectedProduct.price,
                    quantity: nextQty,
                };

                modalHint.textContent = 'Produk masuk ke keranjang.';
                renderCart();
            });

            modalCheckoutNow?.addEventListener('click', function () {
                if (!canCheckout) {
                    modalHint.textContent = 'Mode preview, checkout dinonaktifkan.';
                    return;
                }
                if (!selectedProduct) {
                    return;
                }

                const qty = getModalQty();
                if (qty > selectedProduct.stock) {
                    modalHint.textContent = 'Qty melebihi stok tersedia.';
                    return;
                }

                Object.keys(cart).forEach(function (key) {
                    delete cart[key];
                });

                cart[selectedProduct.id] = {
                    product_id: selectedProduct.id,
                    name: selectedProduct.name,
                    price: selectedProduct.price,
                    quantity: qty,
                };

                renderCart();
                closeProductDetail();
                openCheckoutDrawer();
            });

            closeProductModal?.addEventListener('click', closeProductDetail);
            productModalOverlay?.addEventListener('click', function (event) {
                if (event.target === productModalOverlay) {
                    closeProductDetail();
                }
            });

            openCheckoutBtn?.addEventListener('click', function () {
                if (!canCheckout) {
                    return;
                }
                openCheckoutDrawer();
            });

            closeCheckoutBtn?.addEventListener('click', closeCheckoutDrawer);
            checkoutOverlay?.addEventListener('click', closeCheckoutDrawer);

            const setView = function (mode) {
                const isTiles = mode === 'tiles';
                marketGrid?.classList.toggle('hidden', isTiles);
                marketTiles?.classList.toggle('hidden', !isTiles);

                viewGridBtn?.classList.toggle('bg-white', !isTiles);
                viewGridBtn?.classList.toggle('shadow-sm', !isTiles);
                viewTilesBtn?.classList.toggle('bg-white', isTiles);
                viewTilesBtn?.classList.toggle('shadow-sm', isTiles);

                localStorage.setItem('market-view-mode', isTiles ? 'tiles' : 'grid');
            };

            viewGridBtn?.addEventListener('click', function () {
                setView('grid');
            });

            viewTilesBtn?.addEventListener('click', function () {
                setView('tiles');
            });

            const savedView = localStorage.getItem('market-view-mode');
            setView(savedView === 'tiles' ? 'tiles' : 'grid');
            renderCart();
        })();
    </script>
</x-app-layout>
