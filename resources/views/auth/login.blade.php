<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 10px 40px rgba(79, 70, 229, 0.08), 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #eef0f5;
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-header .icon-wrap {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(79,70,229,.25);
        }

        .login-header .icon-wrap svg {
            width: 26px;
            height: 26px;
            color: #fff;
        }

        .login-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 4px;
        }

        .login-header p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .field-group {
            margin-bottom: 18px;
        }

        .field-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #9ca3af;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 11px 14px 11px 40px !important;
            border-radius: 10px !important;
            border: 1px solid #e5e7eb !important;
            font-size: 14px !important;
            transition: border-color .15s, box-shadow .15s !important;
        }

        .input-wrap input:focus {
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 3px rgba(124,58,237,.12) !important;
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 16px 0 22px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #4b5563;
            cursor: pointer;
        }

        .remember-label input {
            margin-right: 8px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            color: #4f46e5;
        }

        .forgot-link {
            font-size: 13px;
            color: #6d28d9;
            font-weight: 500;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login-custom {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 12px 28px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            letter-spacing: .3px !important;
            transition: transform .15s, box-shadow .15s !important;
        }

        .btn-login-custom:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(79,70,229,.4) !important;
        }

        .btn-login-custom:active {
            transform: translateY(0) !important;
        }
    </style>

    <div class="login-card">
        <div class="login-header">
            <div class="icon-wrap">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to access your attendance dashboard</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Username or Email -->
            <div class="field-group">
                <x-input-label for="login" :value="__('Username or Email')" />
                <div class="input-wrap">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <x-text-input id="login" class="block w-full" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" placeholder="Enter your username or email" />
                </div>
                <x-input-error :messages="$errors->get('login')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="field-group">
                <x-input-label for="password" :value="__('Password')" />
                <div class="input-wrap">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <x-text-input id="password" class="block w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password"
                                    placeholder="Enter your password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me + Forgot Password -->
            <div class="row-between">
                <label for="remember_me" class="remember-label">
                    <input id="remember_me" type="checkbox" name="remember">
                    {{ __('Remember me') }}
                </label>

                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-login-custom text-white">
                {{ __('Log in') }}
            </button>
        </form>
    </div>
</x-guest-layout>