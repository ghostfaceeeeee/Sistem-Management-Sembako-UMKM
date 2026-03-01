<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Marketplace Management
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
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
                        <h3 class="text-lg font-bold text-slate-900">Kelola Pesanan Marketplace</h3>
                        <p class="mt-1 text-sm text-slate-600">Panel admin/staff untuk monitor dan update status pesanan customer.</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('sales.index', ['preview_customer' => 1]) }}"
                           class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-800">
                            Preview Customer
                        </a>
                        <form method="GET" action="{{ route('sales.index') }}" class="flex items-center gap-2">
                            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900">
                                <option value="">Semua Status</option>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>
                                        {{ strtoupper($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white">Filter</button>
                            @if(request()->filled('status'))
                                <a href="{{ route('sales.index') }}" class="rounded-lg bg-slate-200 px-3 py-2 text-sm font-semibold text-slate-800">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600 border-b border-slate-200">
                                <th class="px-3 py-2">Order</th>
                                <th class="px-3 py-2">Customer</th>
                                <th class="px-3 py-2">Item</th>
                                <th class="px-3 py-2">Total</th>
                                <th class="px-3 py-2">Pembayaran</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-b border-slate-200/70 align-top">
                                    <td class="px-3 py-3">
                                        <p class="font-semibold text-slate-900">{{ $order->order_code }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </td>
                                    <td class="px-3 py-3">
                                        <p class="font-semibold text-slate-900">{{ $order->user->name ?? '-' }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $order->user->email ?? '-' }}</p>
                                    </td>
                                    <td class="px-3 py-3">
                                        <p class="font-semibold text-slate-900">{{ $order->items->sum('quantity') }} qty</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ $order->items->count() }} produk</p>
                                    </td>
                                    <td class="px-3 py-3 font-semibold text-slate-900">
                                        Rp {{ number_format((int) $order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-slate-700">
                                        {{ $order->payment_method ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('orders.show', $order) }}"
                                               class="rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800">
                                                Detail
                                            </a>
                                            <form method="POST" action="{{ route('orders.status.update', $order) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs text-slate-900">
                                                    @foreach($statusOptions as $status)
                                                        <option value="{{ $status }}" @selected($order->status === $status)>
                                                            {{ strtoupper($status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="rounded-lg bg-slate-900 px-2.5 py-1.5 text-xs font-semibold text-white">
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-6 text-center text-slate-500">Belum ada pesanan marketplace.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
