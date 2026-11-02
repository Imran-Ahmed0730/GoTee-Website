<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GoTee') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
</head>
<body>
    <main class="container min-vh-100 d-flex flex-column justify-content-center py-5">
        <div class="mx-auto" style="max-width: 44rem">
            <a href="{{ route('login') }}" class="navbar-brand d-inline-flex align-items-center gap-2 mb-5 text-decoration-none">
                <span class="app-brand-mark">G</span>
                <span class="fw-semibold text-dark">GoTee</span>
            </a>
            <p class="text-uppercase small fw-semibold text-success mb-2">Software &amp; hardware engineering</p>
            <h1 class="display-4 fw-semibold mb-3">Technology, thoughtfully engineered.</h1>
            <p class="lead text-secondary mb-4">From connected products and embedded systems to software and production platforms, GoTee helps teams build and operate real-world technology.</p>
            <a href="{{ route('login') }}" class="btn app-form-button px-4">{{ __('Sign in') }}</a>
        </div>
    </main>
</body>
</html>
