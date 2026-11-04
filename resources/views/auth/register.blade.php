<x-guest-layout>
    @section('title', 'Create account')

    <div class="mb-4">
        <div class="auth-kicker">Get started</div>
        <h1 class="auth-heading mt-2 mb-2">Create your account</h1>
        <p class="auth-description mb-0">Set up your GoTee access to collaborate on engineering work.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label app-form-label auth-label">{{ __('Name') }}</label>
            <input id="name" class="form-control app-form-control auth-input" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your name" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="app-form-error auth-errors" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label app-form-label auth-label">{{ __('Email address') }}</label>
            <input id="email" class="form-control app-form-control auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="app-form-error auth-errors" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label app-form-label auth-label">{{ __('Password') }}</label>
            <input id="password" class="form-control app-form-control auth-input" type="password" name="password" placeholder="Create a password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="app-form-error auth-errors" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label app-form-label auth-label">{{ __('Confirm password') }}</label>
            <input id="password_confirmation" class="form-control app-form-control auth-input" type="password" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="app-form-error auth-errors" />
        </div>

        <button type="submit" class="btn app-form-button auth-button w-100">{{ __('Create account') }}</button>
        <p class="text-center text-secondary small mb-0 mt-4">
            {{ __('Already registered?') }}
            <a class="auth-link" href="{{ route('login') }}">{{ __('Sign in') }}</a>
        </p>
    </form>
</x-guest-layout>
