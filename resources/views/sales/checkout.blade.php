<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-3 flex-wrap">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Konfirmasi Checkout</h3>
                        <p class="mt-1 text-sm text-slate-600">Dummy step pembayaran dan alamat penerima.</p>
                    </div>
                    <a href="{{ route('sales.index') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700">
                        Kembali ke Marketplace
                    </a>
                </div>

                <form method="POST" action="{{ route('sales.store') }}" class="mt-6 grid grid-cols-1 lg:grid-cols-12 gap-6">
                    @csrf
                    <input type="hidden" name="cart_payload" value="{{ $cartPayload }}">
                    <input type="hidden" name="note" value="{{ $note }}">

                    <div class="lg:col-span-7 space-y-4">
                        <div class="rounded-xl border border-slate-200 p-4">
                            <label for="shipping_address" class="mb-2 block text-sm font-semibold text-slate-800">Alamat Penerima (Dummy)</label>
                            <textarea id="shipping_address" name="shipping_address" rows="4"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900"
                                placeholder="Contoh: Jl. Mawar No. 10, Bandung">{{ old('shipping_address') }}</textarea>
                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="mb-2 text-sm font-semibold text-slate-800">Metode Pembayaran (Dummy)</p>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800">
                                    <input type="radio" name="payment_method" value="Transfer Bank" checked>
                                    Transfer Bank
                                </label>
                                <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800">
                                    <input type="radio" name="payment_method" value="COD">
                                    COD
                                </label>
                                <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800">
                                    <input type="radio" name="payment_method" value="E-Wallet">
                                    E-Wallet
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="text-sm font-bold text-slate-900">Ringkasan Pesanan</p>
                            <div class="mt-3 space-y-2">
                                @foreach($lines as $line)
                                    <div class="rounded-lg border border-slate-200 p-3">
                                        <p class="text-sm font-semibold text-slate-900">{{ $line['name'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $line['quantity'] }} x Rp {{ number_format($line['price'], 0, ',', '.') }}
                                        </p>
                                        <p class="mt-1 text-xs font-bold text-slate-800">
                                            Subtotal: Rp {{ number_format($line['subtotal'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 border-t border-slate-200 pt-3">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-slate-600">Total Bayar</p>
                                    <p class="text-lg font-bold text-slate-900">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <button type="submit"
                                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90">
                                Bayar & Selesaikan Pesanan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
