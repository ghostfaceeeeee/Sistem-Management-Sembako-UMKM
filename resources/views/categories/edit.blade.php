<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kategori</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-5 p-4 rounded-lg border border-red-300/40 bg-red-500/10 text-red-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-2 font-medium">Nama Kategori</label>
                        <input type="text"
                               name="nama"
                               value="{{ old('nama', $category->nama) }}"
                               class="w-full border rounded p-2">
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                            Update
                        </button>

                        <a href="{{ route('categories.index') }}"
                           class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
