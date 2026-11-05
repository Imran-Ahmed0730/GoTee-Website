@extends('admin.master')
@section('title', 'SMTP settings')
@section('content')
    <nav class="site-breadcrumb-nav" aria-label="Breadcrumb">
        <ol class="breadcrumb site-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><span>Dashboard</span></a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.setting.index') }}"><i class="fa-solid fa-gear" aria-hidden="true"></i><span>Settings</span></a></li>
            <li class="breadcrumb-item"><span aria-current="page"><i class="fa-solid fa-envelope" aria-hidden="true"></i><span>SMTP</span></span></li>
        </ol>
    </nav>

    <section class="admin-card">
        <div class="card">
            <div class="card-header">
                <h1 class="h3 mt-3 mb-1">SMTP settings</h1>
                <p class="text-muted mb-0">Mail credentials are saved to the server environment. Leave the password blank to keep the current password.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.update', 'smtp') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="MAIL_MAILER" class="form-label">Mail driver <span class="text-danger">*</span></label>
                        <select id="MAIL_MAILER" name="MAIL_MAILER" class="form-select @error('MAIL_MAILER') is-invalid @enderror" required>
                            @foreach (['smtp' => 'SMTP', 'log' => 'Log', 'array' => 'Array (testing)'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('MAIL_MAILER', $settings['MAIL_MAILER'] ?? 'smtp') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('MAIL_MAILER')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="MAIL_HOST" class="form-label">SMTP host</label>
                        <input id="MAIL_HOST" name="MAIL_HOST" value="{{ old('MAIL_HOST', $settings['MAIL_HOST'] ?? '') }}" placeholder="smtp.example.com" class="form-control @error('MAIL_HOST') is-invalid @enderror" autocomplete="off">
                        @error('MAIL_HOST')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="MAIL_PORT" class="form-label">SMTP port</label>
                            <input id="MAIL_PORT" name="MAIL_PORT" type="number" min="1" max="65535" value="{{ old('MAIL_PORT', $settings['MAIL_PORT'] ?? '') }}" placeholder="587" class="form-control @error('MAIL_PORT') is-invalid @enderror">
                            @error('MAIL_PORT')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="MAIL_SCHEME" class="form-label">Encryption</label>
                            <select id="MAIL_SCHEME" name="MAIL_SCHEME" class="form-select @error('MAIL_SCHEME') is-invalid @enderror">
                                <option value="" @selected(old('MAIL_SCHEME', $settings['MAIL_SCHEME'] ?? '') === '')>None / automatic</option>
                                <option value="smtp" @selected(old('MAIL_SCHEME', $settings['MAIL_SCHEME'] ?? '') === 'smtp')>STARTTLS (smtp)</option>
                                <option value="smtps" @selected(old('MAIL_SCHEME', $settings['MAIL_SCHEME'] ?? '') === 'smtps')>SSL/TLS (smtps)</option>
                            </select>
                            @error('MAIL_SCHEME')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="MAIL_USERNAME" class="form-label">SMTP username</label>
                        <input id="MAIL_USERNAME" name="MAIL_USERNAME" value="{{ old('MAIL_USERNAME', $settings['MAIL_USERNAME'] ?? '') }}" placeholder="SMTP username" class="form-control @error('MAIL_USERNAME') is-invalid @enderror" autocomplete="off">
                        @error('MAIL_USERNAME')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="MAIL_PASSWORD" class="form-label">SMTP password</label>
                        <input id="MAIL_PASSWORD" name="MAIL_PASSWORD" type="password" value="" placeholder="Leave blank to keep the current password" class="form-control @error('MAIL_PASSWORD') is-invalid @enderror" autocomplete="new-password">
                        @error('MAIL_PASSWORD')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="MAIL_FROM_ADDRESS" class="form-label">From email <span class="text-danger">*</span></label>
                        <input id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS" type="email" value="{{ old('MAIL_FROM_ADDRESS', $settings['MAIL_FROM_ADDRESS'] ?? '') }}" placeholder="name@example.com" class="form-control @error('MAIL_FROM_ADDRESS') is-invalid @enderror" required>
                        @error('MAIL_FROM_ADDRESS')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="MAIL_FROM_NAME" class="form-label">From name <span class="text-danger">*</span></label>
                        <input id="MAIL_FROM_NAME" name="MAIL_FROM_NAME" value="{{ old('MAIL_FROM_NAME', $settings['MAIL_FROM_NAME'] ?? '') }}" placeholder="Sender name" class="form-control @error('MAIL_FROM_NAME') is-invalid @enderror" required>
                        @error('MAIL_FROM_NAME')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save SMTP settings</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
