<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Kategori
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
                            Total Kategori:
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-300/25 border border-yellow-300/40 text-gray-900 ml-1">
                                {{ $categories->total() }}
                            </span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('products.index') }}"
                           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            Kembali ke Produk
                        </a>

                        <a href="{{ route('categories.create') }}"
                           class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded font-semibold">
                            + Tambah Kategori
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 rounded-md">
                        <thead>
                            <tr>
                                <th class="border px-4 py-3 w-12">#</th>
                                <th class="border px-4 py-3">Nama</th>
                                <th class="border px-4 py-3 w-40 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $category)
                                <tr>
                                    <td class="border px-4 py-3">
                                        {{ $categories->firstItem() + $index }}
                                    </td>
                                    <td class="border px-4 py-3 font-medium">
                                        {{ $category->nama }}
                                    </td>
                                    <td class="border px-4 py-3 text-center space-x-2">
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                           class="bg-yellow-400 text-black px-3 py-1 rounded text-xs font-semibold">
                                            Edit
                                        </a>

                                        <form action="{{ route('categories.destroy', $category->id) }}"
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
                                    <td colspan="3" class="text-center py-6 text-gray-500">
                                        Belum ada kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $categories->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
