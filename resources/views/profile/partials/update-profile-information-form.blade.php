<header class="mb-4">
    <h2 id="profile-information-title" class="h5 fw-semibold mb-1">{{ __('Profile information') }}</h2>
    <p class="admin-muted small mb-0">{{ __("Update your account name, email address, and profile photo.") }}</p>
</header>

<form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 mb-4">
        <span id="profile-photo-fallback" class="profile-avatar @if ($user->profile_photo_path) d-none @endif">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
        <img id="profile-photo-preview" class="profile-avatar @unless ($user->profile_photo_path) d-none @endunless" @if ($user->profile_photo_path) src="{{ Storage::disk('public')->url($user->profile_photo_path) }}" @endif alt="{{ $user->name }} profile photo">
        <div>
            <label for="profile_photo" class="app-form-label d-block mb-1">{{ __('Profile photo') }}</label>
            <input id="profile_photo" class="form-control form-control-sm" type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            <p class="admin-muted small mt-2 mb-0">JPG, PNG, or WebP. Maximum 2 MB; images must be 2048 × 2048 px or smaller.</p>
            <x-input-error :messages="$errors->get('profile_photo')" class="app-form-error" />
            @if ($user->profile_photo_path)
                <div class="form-check mt-2">
                    <input id="remove_profile_photo" class="form-check-input" type="checkbox" name="remove_profile_photo" value="1">
                    <label class="form-check-label admin-muted small" for="remove_profile_photo">{{ __('Remove current photo') }}</label>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label for="name" class="form-label app-form-label">{{ __('Name') }}</label>
            <input id="name" class="form-control app-form-control" name="name" type="text" value="{{ old('name', $user->name) }}" placeholder="Enter your name" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="app-form-error" />
        </div>
        <div class="col-12 col-md-6">
            <label for="email" class="form-label app-form-label">{{ __('Email address') }}</label>
            <input id="email" class="form-control app-form-control" name="email" type="email" value="{{ old('email', $user->email) }}" placeholder="Enter your email address" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="app-form-error" />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="admin-muted small mt-2 mb-0">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">Resend verification email</button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="small text-success mt-2 mb-0">{{ __('A new verification link has been sent to your email address.') }}</p>
                @endif
            @endif
        </div>
    </div>

    <div class="d-flex align-items-center gap-3 mt-4">
        <button type="submit" class="btn app-form-button px-4">{{ __('Save changes') }}</button>
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
    @csrf
</form>
