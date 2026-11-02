<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - GoTee</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @include('admin.include.style')
    @stack('css')
</head>
<body>
<div class="admin-shell d-lg-flex">
    @include('admin.include.sidebar')
    <button type="button" class="admin-sidebar-backdrop" aria-label="Close navigation" hidden></button>
    <div class="admin-min-width-0 flex-grow-1">
        @include('admin.include.header')
        <main class="admin-content">
            @yield('content')
            @include('admin.include.footer')
        </main>
    </div>
</div>
@include('admin.include.script')
@stack('js')
</body>
</html>
