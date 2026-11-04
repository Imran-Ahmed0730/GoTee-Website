<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign in') · GoTee</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    @vite('resources/js/app.js')
</head>
<body>
<div class="auth-shell row g-0">
    <aside class="auth-brand-panel col-md-5 col-xl-6 d-none d-md-flex flex-column justify-content-between">
        <div class="auth-brand-content">
            <a href="{{ url('/') }}" class="auth-brand d-inline-flex align-items-center gap-3">
                <span class="app-brand-mark">G</span>
                <span class="fs-5 fw-semibold">GoTee</span>
            </a>
            <div class="mt-5 pt-4">
                <div class="auth-kicker mb-3">Technology, thoughtfully engineered</div>
                <h1 class="display-5 fw-semibold lh-sm">From connected devices to intelligent operations.</h1>
                <p class="auth-intro fs-6 lh-lg mt-3">GoTee brings software and hardware together to solve real-world challenges across connected products, embedded systems, and modern production.</p>
            </div>
            <div class="d-flex flex-column gap-2 mt-4">
                <div class="auth-service">
                    <span class="auth-service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m8 8-4 4 4 4m8-8 4 4-4 4m-3-10-2 12"/></svg></span>
                    <span><span class="d-block small fw-semibold">Software &amp; production systems</span><span class="d-block small text-white-50">Business platforms built for the real world</span></span>
                </div>
                <div class="auth-service">
                    <span class="auth-service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="7" y="7" width="10" height="10" rx="2"/><path d="M9 2v3m6-3v3M9 19v3m6-3v3M2 9h3m-3 6h3m14-6h3m-3 6h3"/></svg></span>
                    <span><span class="d-block small fw-semibold">IoT &amp; embedded engineering</span><span class="d-block small text-white-50">Devices, firmware, and connected fleets</span></span>
                </div>
                <div class="auth-service">
                    <span class="auth-service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="4" y="4" width="16" height="12" rx="2"/><path d="M8 20h8m-4-4v4m-5-9 3-3 2 2 3-4"/></svg></span>
                    <span><span class="d-block small fw-semibold">Hardware design &amp; integration</span><span class="d-block small text-white-50">From early prototypes to deployment</span></span>
                </div>
            </div>
        </div>
        <div class="auth-brand-content d-flex align-items-end justify-content-between gap-3">
            <span class="small text-white-50">© {{ now()->year }} GoTee. Technology, from edge to enterprise.</span>
            <div class="auth-grid-art" aria-hidden="true">
                <svg viewBox="0 0 240 190" fill="none">
                    <path d="M35 95h42l22-38h50l22 38h34M77 95l22 38h50l22-38m-67-38v76m45-76v76" stroke="#8bb8e8" stroke-opacity=".72" stroke-width="1.5"/>
                    <circle cx="35" cy="95" r="6" fill="#8bb8e8"/><circle cx="77" cy="95" r="5" fill="#0b1f33" stroke="#8bb8e8" stroke-width="2"/>
                    <circle cx="99" cy="57" r="5" fill="#0b1f33" stroke="#8bb8e8" stroke-width="2"/><circle cx="149" cy="57" r="5" fill="#0b1f33" stroke="#8bb8e8" stroke-width="2"/>
                    <circle cx="171" cy="95" r="5" fill="#0b1f33" stroke="#8bb8e8" stroke-width="2"/><circle cx="205" cy="95" r="6" fill="#8bb8e8"/>
                    <circle cx="99" cy="133" r="5" fill="#0b1f33" stroke="#8bb8e8" stroke-width="2"/><circle cx="149" cy="133" r="5" fill="#0b1f33" stroke="#8bb8e8" stroke-width="2"/>
                    <rect x="112" y="78" width="25" height="34" rx="5" fill="#8bb8e8" fill-opacity=".16" stroke="#8bb8e8" stroke-opacity=".8"/>
                    <path d="M120 87h9m-9 6h9m-9 6h6" stroke="#8bb8e8" stroke-width="1.5"/>
                </svg>
            </div>
        </div>
    </aside>

    <main class="auth-content-panel col-md-7 col-xl-6">
        <div class="auth-content">
            <a href="{{ url('/') }}" class="auth-mobile-brand text-decoration-none">
                <span class="app-brand-mark">G</span>
                GoTee
            </a>
            <div class="auth-card">
                {{ $slot }}
            </div>
            <div class="auth-footer">Secure access to GoTee engineering operations.</div>
        </div>
    </main>
</div>
@stack('js')
</body>
</html>
