<header class="mb-4">
    <h2 id="delete-account-title" class="h5 fw-semibold mb-1">{{ __('Delete account') }}</h2>
    <p class="admin-muted small mb-0">{{ __('Permanently remove your account and its data. This action cannot be undone.') }}</p>
</header>

<form method="post" action="{{ route('admin.profile.destroy') }}"
    data-confirm
    data-confirm-title="{{ __('Delete your account?') }}"
    data-confirm-text="{{ __('Your account and its data will be permanently removed.') }}"
    data-confirm-button="{{ __('Delete account') }}"
    data-cancel-button="{{ __('Cancel') }}">
    @csrf
    @method('delete')

    <div class="row align-items-end g-3">
        <div class="col-12 col-md-7">
            <label for="user-deletion-password" class="form-label app-form-label">{{ __('Confirm with your password') }} <span class="text-danger">*</span></label>
            <input id="user-deletion-password" name="password" type="password" class="form-control app-form-control" placeholder="Enter your password to confirm" autocomplete="current-password" required>
            <x-input-error :messages="$errors->userDeletion->get('password')" class="app-form-error" />
        </div>
        <div class="col-12 col-md-auto">
            <button type="submit" class="btn btn-outline-danger rounded-3 px-4">{{ __('Delete account') }}</button>
        </div>
    </div>
</form>
