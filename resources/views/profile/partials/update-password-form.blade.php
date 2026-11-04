<header class="mb-4">
    <h2 id="update-password-title" class="h5 fw-semibold mb-1">{{ __('Update password') }}</h2>
    <p class="admin-muted small mb-0">{{ __('Choose a strong password to keep your engineering workspace secure.') }}</p>
</header>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="row g-3">
        <div class="col-12">
            <label for="update_password_current_password" class="form-label app-form-label">{{ __('Current password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control app-form-control" placeholder="Enter your current password" autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="app-form-error" />
        </div>
        <div class="col-12 col-md-6">
            <label for="update_password_password" class="form-label app-form-label">{{ __('New password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control app-form-control" placeholder="Create a new password" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="app-form-error" />
        </div>
        <div class="col-12 col-md-6">
            <label for="update_password_password_confirmation" class="form-label app-form-label">{{ __('Confirm new password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control app-form-control" placeholder="Confirm your new password" autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="app-form-error" />
        </div>
    </div>

    <div class="d-flex align-items-center gap-3 mt-4">
        <button type="submit" class="btn app-form-button px-4">{{ __('Update password') }}</button>
    </div>
</form>
