<x-guest-layout>
    @section('title', 'Reset your password')

    <div class="mb-4">
        <div class="auth-kicker">Account recovery</div>
        <h1 class="auth-heading mt-2 mb-2">{{ __('Forgot your password?') }}</h1>
        <p class="auth-description mb-0">{{ __('Enter the email address associated with your account and we will send you a password reset link.') }}</p>
    </div>

    <x-auth-session-status class="app-status auth-status mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label app-form-label auth-label">{{ __('Email address') }}</label>
            <input id="email" class="form-control app-form-control auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="app-form-error auth-errors" />
        </div>

        <button type="submit" class="btn app-form-button auth-button w-100">{{ __('Send reset link') }}</button>
        <div class="text-center mt-4">
            <a class="auth-link" href="{{ route('login') }}">{{ __('Back to sign in') }}</a>
        </div>
    </form>
</x-guest-layout>
