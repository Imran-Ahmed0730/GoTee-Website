<x-guest-layout>
    @section('title', 'Verify your email')

    <div class="mb-4">
        <div class="auth-kicker">One last step</div>
        <h1 class="auth-heading mt-2 mb-2">{{ __('Verify your email') }}</h1>
        <p class="auth-description mb-0">{{ __('Thanks for signing up. Verify your email address using the link we sent you to finish setting up your GoTee account.') }}</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="app-status auth-status mb-3" role="status">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex flex-column gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn app-form-button auth-button w-100">{{ __('Resend verification email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="btn btn-link auth-link text-decoration-none">{{ __('Sign out') }}</button>
        </form>
    </div>
</x-guest-layout>
