<x-guest-layout>
    @section('title', 'Choose a new password')

    <div class="mb-4">
        <div class="auth-kicker">Account recovery</div>
        <h1 class="auth-heading mt-2 mb-2">{{ __('Choose a new password') }}</h1>
        <p class="auth-description mb-0">{{ __('Create a new password to securely access your GoTee account.') }}</p>
    </div>

    <x-auth-session-status class="app-status auth-status mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label app-form-label auth-label">{{ __('Email address') }}</label>
            <input id="email" class="form-control app-form-control auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Enter your email address" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="app-form-error auth-errors" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label app-form-label auth-label">{{ __('New password') }}</label>
            <input id="password" class="form-control app-form-control auth-input" type="password" name="password" placeholder="Create a new password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="app-form-error auth-errors" />
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label app-form-label auth-label">{{ __('Confirm new password') }}</label>
            <input id="password_confirmation" class="form-control app-form-control auth-input" type="password" name="password_confirmation" placeholder="Confirm your new password" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="app-form-error auth-errors" />
        </div>

        <button type="submit" class="btn app-form-button auth-button w-100">{{ __('Reset password') }}</button>
    </form>
</x-guest-layout>
