<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Overview') · GoTee Studio</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7f8f5] font-sans text-[#18231f] antialiased">
<div class="min-h-screen lg:grid lg:grid-cols-[252px_minmax(0,1fr)]">
    <aside class="hidden min-h-screen flex-col bg-[#14231e] px-5 py-6 text-white lg:flex">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-2">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-[#c4f06b] text-lg font-bold text-[#14231e]">G</span>
            <span>
                <span class="block text-lg font-semibold tracking-tight">GoTee</span>
                <span class="block text-[11px] font-medium uppercase tracking-[0.18em] text-white/45">Studio admin</span>
            </span>
        </a>

        <div class="mt-12 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/40">Workspace</div>
        <nav class="mt-3 flex flex-col gap-1" aria-label="Main navigation">
            <a href="{{ route('dashboard') }}" aria-current="page" class="flex items-center gap-3 rounded-xl bg-white/10 px-3 py-3 text-sm font-medium text-white">
                <svg class="h-5 w-5 text-[#c4f06b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
                Overview
            </a>
            <span class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 8h16l-1.2 12H5.2L4 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
                Products
            </span>
            <span class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 7.5h16v13H4z"/><path d="M8 7.5V5h8v2.5M4 12h16"/></svg>
                Orders
                <span class="ml-auto rounded-full bg-[#c4f06b] px-2 py-0.5 text-[10px] font-semibold text-[#14231e]">8</span>
            </span>
            <span class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M3 20v-1.5A4.5 4.5 0 0 1 7.5 14h3a4.5 4.5 0 0 1 4.5 4.5V20M16 4.8a3.5 3.5 0 0 1 0 6.4m2 3a4.5 4.5 0 0 1 3 4.3V20"/></svg>
                Customers
            </span>
            <span class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg>
                Analytics
            </span>
        </nav>

        <div class="mt-10 px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/40">Store</div>
        <nav class="mt-3 flex flex-col gap-1" aria-label="Store navigation">
            <span class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/></svg>
                Discounts
            </span>
            <span class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M8 3.7c2.4 2.5 3.5 5.3 3.5 8.3s-1.1 5.8-3.5 8.3M16 3.7c-2.4 2.5-3.5 5.3-3.5 8.3s1.1 5.8 3.5 8.3"/></svg>
                Online store
            </span>
        </nav>

        <div class="mt-auto rounded-2xl border border-white/10 bg-white/[0.04] p-4">
            <div class="flex items-center gap-2 text-xs font-medium text-white/75">
                <span class="h-2 w-2 rounded-full bg-[#c4f06b]"></span>
                Your store is looking good
            </div>
            <p class="mt-2 text-xs leading-5 text-white/45">A little progress adds up. Keep your catalog fresh this week.</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm text-white/55 transition hover:bg-white/10 hover:text-white">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></svg>
                Sign out
            </button>
        </form>
    </aside>

    <div class="min-w-0">
        <header class="flex h-[76px] items-center justify-between border-b border-[#e9ece6] bg-white px-5 sm:px-8 lg:px-10">
            <div class="flex items-center gap-3 lg:hidden">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-[#14231e] font-bold text-[#c4f06b]">G</span>
                <span class="font-semibold tracking-tight">GoTee</span>
            </div>
            <div class="hidden items-center gap-3 rounded-xl bg-[#f6f7f4] px-3.5 py-2.5 md:flex md:w-72">
                <svg class="h-4 w-4 text-[#89928c]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>
                <span class="text-sm text-[#99a19c]">Search your store</span>
                <span class="ml-auto rounded border border-[#e3e7e1] bg-white px-1.5 py-0.5 text-[10px] text-[#929a94]">⌘ K</span>
            </div>
            <div class="ml-auto flex items-center gap-4 sm:gap-6">
                <span class="hidden text-sm text-[#727c75] sm:block">{{ now()->format('D, M j') }}</span>
                <span class="relative grid h-10 w-10 place-items-center rounded-xl border border-[#ecefe9] text-[#5d6961]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/></svg>
                    <span class="absolute right-2 top-2 h-2 w-2 rounded-full border-2 border-white bg-[#ee8067]"></span>
                </span>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3">
                    <span class="hidden text-right sm:block">
                        <span class="block text-sm font-semibold">{{ auth()->user()->name }}</span>
                        <span class="block text-xs text-[#8b948e]">Store admin</span>
                    </span>
                    <span class="grid h-10 w-10 place-items-center rounded-full bg-[#e7eedc] text-sm font-semibold text-[#49633a]">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                </a>
            </div>
        </header>
        <main class="px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
            <div class="mx-auto max-w-[1440px]">
                @yield('content')
                <footer class="mt-10 flex flex-col gap-2 border-t border-[#e9ece6] pt-5 text-xs text-[#929a94] sm:flex-row sm:items-center sm:justify-between">
                    <span>© {{ now()->year }} GoTee Studio</span>
                    <span>Made for the people who make things.</span>
                </footer>
            </div>
        </main>
    </div>
</div>
</body>
</html>
