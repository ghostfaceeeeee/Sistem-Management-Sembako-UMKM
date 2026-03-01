<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Detail Pesanan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div>
                        <p class="text-xs text-slate-500">Kode Pesanan</p>
                        <h3 class="text-lg font-bold text-slate-900">{{ $order->order_code }}</h3>
                        <p class="mt-1 text-xs text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ strtoupper($order->status) }}
                        </span>
                        <p class="mt-2 text-sm text-slate-600">
                            Total: <span class="font-bold text-slate-900">Rp {{ number_format((int) $order->total_amount, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600 border-b border-slate-200">
                                <th class="px-3 py-2">Produk</th>
                                <th class="px-3 py-2">Qty</th>
                                <th class="px-3 py-2">Harga</th>
                                <th class="px-3 py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b border-slate-200/70">
                                    <td class="px-3 py-2 font-semibold text-slate-900">{{ $item->product->nama_barang ?? '-' }}</td>
                                    <td class="px-3 py-2 text-slate-700">{{ (int) $item->quantity }}</td>
                                    <td class="px-3 py-2 text-slate-700">Rp {{ number_format((int) $item->price, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2 font-semibold text-slate-900">Rp {{ number_format((int) $item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 grid gap-3 md:grid-cols-2 text-sm">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs text-slate-500">Customer</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $order->user->name ?? '-' }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $order->user->email ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <p class="text-xs text-slate-500">Pembayaran</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $order->payment_method ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 md:col-span-2">
                        <p class="text-xs text-slate-500">Alamat Penerima</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $order->shipping_address ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 md:col-span-2">
                        <p class="text-xs text-slate-500">Catatan</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $order->note ?? '-' }}</p>
                    </div>
                </div>

                <div class="mt-5">
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('orders.my') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-800">
                            Kembali ke Pesanan Saya
                        </a>
                    @else
                        <a href="{{ route('sales.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-800">
                            Kembali ke Marketplace Management
                        </a>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

