<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
        <p class="text-sm text-gray-500 mt-1">
            Masukkan email user, lalu kami kirim link reset password.
        </p>
    </div>

    <x-auth-session-status class="mb-4 text-sm text-green-300" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
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
                          placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <x-primary-button class="w-full justify-center py-3 text-sm font-bold rounded-lg">
            {{ __('Email Password Reset Link') }}
        </x-primary-button>
    </form>
</x-guest-layout>
