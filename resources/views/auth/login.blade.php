<x-guest-layout>
    @section('title', 'Sign in')

    <div class="mb-4">
        <div class="auth-kicker">Welcome back</div>
        <h1 class="auth-heading mt-2 mb-2">Sign in to GoTee</h1>
        <p class="auth-description mb-0">Access your engineering projects and connected operations.</p>
    </div>

    <x-auth-session-status class="app-status auth-status mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label app-form-label auth-label">{{ __('Email address') }}</label>
            <input id="email" class="form-control app-form-control auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="app-form-error auth-errors" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label app-form-label auth-label">{{ __('Password') }}</label>
            <input id="password" class="form-control app-form-control auth-input" type="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="app-form-error auth-errors" />
        </div>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <label for="remember_me" class="d-inline-flex align-items-center gap-2 text-secondary small">
                <input id="remember_me" type="checkbox" class="form-check-input auth-check m-0" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
            @endif
        </div>

        <button type="submit" class="btn app-form-button auth-button w-100">{{ __('Sign in') }}</button>

        @if (Route::has('register'))
            <p class="text-center text-secondary small mb-0 mt-4">
                {{ __("Don't have an account?") }}
                <a class="auth-link" href="{{ route('register') }}">{{ __('Create account') }}</a>
            </p>
        @endif
    </form>
</x-guest-layout>
