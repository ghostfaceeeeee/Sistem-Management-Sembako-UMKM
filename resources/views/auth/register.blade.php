<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Buat Akun Marketplace</h1>
        <p class="mt-1 text-sm text-gray-500">Daftar sebagai customer untuk belanja di marketplace.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name"
                          class="mt-1 block w-full"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required
                          autofocus
                          autocomplete="name"
                          placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                          class="mt-1 block w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autocomplete="username"
                          placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                          class="mt-1 block w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password"
                          placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation"
                          class="mt-1 block w-full"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password"
                          placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
        </div>

        <x-primary-button class="w-full justify-center py-3 text-sm font-bold rounded-lg">
            {{ __('Daftar') }}
        </x-primary-button>
    </form>

    <p class="mt-4 text-center text-sm text-gray-600">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-gray-800 underline underline-offset-4">
            Masuk di sini
        </a>
    </p>
</x-guest-layout>
