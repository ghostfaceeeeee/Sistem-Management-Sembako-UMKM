<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Pesanan Saya
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @forelse($orders as $order)
                <section class="rounded-2xl border border-slate-200 bg-white p-6">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div>
                            <p class="text-xs text-slate-500">Kode Pesanan</p>
                            <h3 class="text-lg font-bold text-slate-900">{{ $order->order_code }}</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ strtoupper($order->status) }}
                            </span>
                            <p class="mt-2 text-sm text-slate-600">
                                Total: <span class="font-bold text-slate-900">Rp {{ number_format((int) $order->total_amount, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-600">
                                    <th class="px-3 py-2">Produk</th>
                                    <th class="px-3 py-2">Qty</th>
                                    <th class="px-3 py-2">Harga</th>
                                    <th class="px-3 py-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-t border-slate-200/80">
                                        <td class="px-3 py-2 font-semibold text-slate-900">{{ $item->product->nama_barang ?? '-' }}</td>
                                        <td class="px-3 py-2 text-slate-700">{{ (int) $item->quantity }}</td>
                                        <td class="px-3 py-2 text-slate-700">Rp {{ number_format((int) $item->price, 0, ',', '.') }}</td>
                                        <td class="px-3 py-2 font-semibold text-slate-900">Rp {{ number_format((int) $item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 grid gap-3 md:grid-cols-2 text-sm">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Metode Pembayaran</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $order->payment_method ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Alamat Penerima</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $order->shipping_address ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('orders.show', $order) }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-800">
                            Lihat Detail
                        </a>
                    </div>
                </section>
            @empty
                <section class="rounded-2xl border border-slate-200 bg-white p-8 text-center">
                    <p class="text-slate-700">Belum ada pesanan.</p>
                    <a href="{{ route('sales.index') }}" class="mt-3 inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                        Mulai Belanja
                    </a>
                </section>
            @endforelse

            <div>
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
