<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        .btn-login-custom {
            background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 10px 28px !important;
            font-weight: 600 !important;
            letter-spacing: .3px !important;
            transition: transform .15s, box-shadow .15s !important;
        }
        .btn-login-custom:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(79,70,229,.4) !important;
        }
    </style>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username or Email -->
        <div>
            <x-input-label for="login" :value="__('Username or Email')" />
            <x-text-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-center mt-4">
            <button type="submit" class="btn-login-custom text-white">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>