<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Masuk ke Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola inventory UMKM kamu dengan lebih rapi.</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm text-green-300" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                          class="block mt-1 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus
                          autocomplete="username"
                          placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                          class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password"
                          placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me"
                       type="checkbox"
                       class="rounded border-gray-300 text-gray-800 shadow-sm focus:ring-yellow-400"
                       name="remember">
                <span class="text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-800 underline underline-offset-4"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="w-full justify-center py-3 text-sm font-bold rounded-lg">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="mt-4 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-gray-800 underline underline-offset-4">
                Daftar sekarang
            </a>
        </p>
    @endif
</x-guest-layout>
