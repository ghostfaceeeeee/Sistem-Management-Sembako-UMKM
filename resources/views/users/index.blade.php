<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen User</h2>
</x-slot>

<div class="py-8">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-lg border border-emerald-300/40 bg-emerald-500/10 text-emerald-100">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-lg border border-red-300/40 bg-red-500/10 text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div>
                <p class="text-xs uppercase tracking-wider text-gray-500">Access Control</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">
                    Total User:
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-300/25 border border-yellow-300/40 text-gray-900 ml-1">
                        {{ $users->total() }}
                    </span>
                </p>
            </div>

            <a href="{{ route('users.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900 font-semibold">
                + Tambah User
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-md">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-3 border">#</th>
                        <th class="px-4 py-3 border">Nama</th>
                        <th class="px-4 py-3 border">Email</th>
                        <th class="px-4 py-3 border">Role</th>
                        <th class="px-4 py-3 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $index => $user)
                    <tr>
                        <td class="px-4 py-3 border">{{ $users->firstItem() + $index }}</td>
                        <td class="px-4 py-3 border font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 border">{{ $user->email }}</td>
                        <td class="px-4 py-3 border">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'stock-badge stock-badge-warning' : 'stock-badge stock-badge-safe' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border text-center space-x-2">
                            <form action="{{ route('users.send-reset-link', $user) }}" method="POST" class="inline">
                                @csrf
                                <button class="bg-gray-700 text-white px-3 py-1 rounded text-xs hover:bg-gray-800">
                                    Reset Email
                                </button>
                            </form>

                            <a href="{{ route('users.edit', $user) }}" class="inline-block bg-yellow-400 text-black px-3 py-1 rounded text-xs font-semibold hover:bg-yellow-500">
                                Edit
                            </a>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">Belum ada user.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

</div>
</div>
</x-app-layout>
