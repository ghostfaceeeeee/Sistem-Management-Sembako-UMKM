<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Supplier
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg border border-emerald-300/40 bg-emerald-500/10 text-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-500">Master Data</p>
                        <p class="text-lg font-semibold text-gray-800 mt-1">
                            Total Supplier:
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-300/25 border border-yellow-300/40 text-gray-900 ml-1">
                                {{ $suppliers->total() }}
                            </span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('products.index') }}"
                           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            Kembali ke Produk
                        </a>

                        <a href="{{ route('suppliers.create') }}"
                           class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded font-semibold">
                            + Tambah Supplier
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-md">
                        <thead>
                            <tr>
                                <th class="border px-4 py-3 w-12">#</th>
                                <th class="border px-4 py-3">Nama</th>
                                <th class="border px-4 py-3">Alamat</th>
                                <th class="border px-4 py-3">No Telp</th>
                                <th class="border px-4 py-3">Email</th>
                                <th class="border px-4 py-3 w-40 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $index => $supplier)
                                <tr>
                                    <td class="border px-4 py-3">
                                        {{ $suppliers->firstItem() + $index }}
                                    </td>
                                    <td class="border px-4 py-3 font-medium">
                                        {{ $supplier->nama_supplier }}
                                    </td>
                                    <td class="border px-4 py-3">
                                        {{ $supplier->alamat }}
                                    </td>
                                    <td class="border px-4 py-3">
                                        {{ $supplier->no_telp }}
                                    </td>
                                    <td class="border px-4 py-3">
                                        {{ $supplier->email }}
                                    </td>
                                    <td class="border px-4 py-3 text-center space-x-2">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                           class="bg-yellow-400 text-black px-3 py-1 rounded text-xs font-semibold">
                                            Edit
                                        </a>

                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs"
                                                    onclick="return confirm('Yakin hapus?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500">
                                        Belum ada supplier.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $suppliers->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
