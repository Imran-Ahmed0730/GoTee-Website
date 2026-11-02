<x-guest-layout>
    @section('title', 'Confirm your password')

    <div class="mb-4">
        <div class="auth-kicker">Security check</div>
        <h1 class="auth-heading mt-2 mb-2">{{ __('Confirm your password') }}</h1>
        <p class="auth-description mb-0">{{ __('This is a secure area. Confirm your password before continuing.') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label app-form-label auth-label">{{ __('Password') }}</label>
            <input id="password" class="form-control app-form-control auth-input" type="password" name="password" required autofocus autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="app-form-error auth-errors" />
        </div>

        <button type="submit" class="btn app-form-button auth-button w-100">{{ __('Confirm and continue') }}</button>
    </form>
</x-guest-layout>
