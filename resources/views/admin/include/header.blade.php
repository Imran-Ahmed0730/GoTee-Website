<header class="admin-topbar d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="admin-menu-toggle" aria-label="Expand navigation" aria-controls="admin-sidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="d-flex align-items-center gap-2 d-lg-none">
            <span class="admin-mobile-brand">G</span>
            <span class="fw-semibold">GoTee</span>
        </div>
    </div>

    <div class="ms-auto d-flex align-items-center gap-3 gap-sm-4">
        <span class="admin-muted d-none d-sm-inline small">{{ now()->format('D, M j') }}</span>
        <span class="admin-icon-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/></svg>
                    <span class="admin-notification-dot"></span>
                </span>
        <div class="position-relative">
            <button type="button" class="admin-profile-trigger d-flex align-items-center gap-3 text-dark" aria-label="Open profile menu" aria-controls="admin-profile-menu" aria-expanded="false">
                <span class="d-none d-sm-block text-end">
                    <span class="d-block small fw-semibold">{{ auth()->user()->name }}</span>
                    <span class="admin-muted d-block" style="font-size: .75rem">Administrator</span>
                </span>
                @if (auth()->user()->profile_photo_path)
                    <img class="admin-avatar" src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }} profile photo" style="object-fit: cover">
                @else
                    <span class="admin-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
            </button>
            <div id="admin-profile-menu" class="admin-profile-menu" hidden>
                <a href="{{ route('profile.edit') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                    Profile settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
