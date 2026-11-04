
@extends('admin.master')
@section('title', 'Profile Settings')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-user-gear" aria-hidden="true"></i><span>Profile settings</span></span></li>
        </ol>
    </nav>
    <div class="mb-4">
        <div class="profile-eyebrow text-uppercase fw-semibold">Account</div>
        <h1 class="h3 fw-semibold mt-2 mb-1">Profile settings</h1>
        <p class="admin-muted mb-0">Manage your GoTee account, sign-in details, and profile photo.</p>
    </div>

    <div class="d-flex flex-column gap-4">
        <section class="admin-card p-4 p-md-5" aria-labelledby="profile-information-title">
            @include('profile.partials.update-profile-information-form', ['user' => $user])
        </section>

        <section class="admin-card p-4 p-md-5" aria-labelledby="update-password-title">
            @include('profile.partials.update-password-form')
        </section>

        <section class="admin-card p-4 p-md-5" aria-labelledby="delete-account-title">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
@endsection
@push('js')
    <script>
        const $photoInput = $('#profile_photo');
        const $photoPreview = $('#profile-photo-preview');
        const $photoFallback = $('#profile-photo-fallback');
        const $removePhoto = $('#remove_profile_photo');

        $photoInput.on('change', function () {
            const file = this.files[0];

            if (file) {
                $photoPreview.attr('src', URL.createObjectURL(file)).removeClass('d-none');
                $photoFallback.addClass('d-none');
                $removePhoto.prop('checked', false);
            }
        });

        $removePhoto.on('change', function () {
            if (this.checked && $photoInput[0].files.length === 0) {
                $photoPreview.addClass('d-none');
                $photoFallback.removeClass('d-none');
            } else if ($photoPreview.attr('src')) {
                $photoPreview.removeClass('d-none');
                $photoFallback.addClass('d-none');
            }
        });

        $(document).on('submit', 'form[data-confirm]', function (event) {
            const form = this;

            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return;
            }

            event.preventDefault();

            Swal.fire({
                title: form.dataset.confirmTitle || 'Are you sure?',
                text: form.dataset.confirmText || 'This action cannot be undone.',
                icon: form.dataset.confirmIcon || 'warning',
                showCancelButton: true,
                reverseButtons: true,
                focusCancel: true,
                confirmButtonText: form.dataset.confirmButton || 'Confirm',
                cancelButtonText: form.dataset.cancelButton || 'Cancel',

            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.requestSubmit();
                }
            });
        });

    </script>
@endpush
