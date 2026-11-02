<aside id="admin-sidebar" class="admin-sidebar flex-shrink-0" aria-label="Main navigation">
    <a href="{{ route('dashboard') }}" class="admin-brand d-flex align-items-center gap-3 px-2 pb-2">
        <span class="app-brand-mark">G</span>
        <span class="admin-brand-copy">
                <span class="d-block fs-5 fw-semibold">GoTee</span>
        </span>
    </a>

    <div class="admin-nav-label">Projects</div>
    <nav class="d-flex flex-column gap-1" aria-label="Main navigation">
        <a href="{{ route('dashboard') }}" aria-label="Overview" title="Overview" aria-current="page" class="admin-nav-link active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
            <span class="admin-nav-text">Overview</span>
        </a>
        <button type="button" class="admin-nav-link admin-submenu-toggle" aria-label="Software submenu" title="Software" aria-expanded="false" aria-controls="software-submenu" data-submenu-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 8h16l-1.2 12H5.2L4 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
                <span class="admin-nav-text">Software</span>
                <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </button>
        <div id="software-submenu" class="admin-submenu" hidden>
            <a class="admin-submenu-link" href="#delivery-tracker-title">Custom software</a>
            <a class="admin-submenu-link" href="#delivery-tracker-title">Production management</a>
        </div>
        <button type="button" class="admin-nav-link admin-submenu-toggle" aria-label="Active projects submenu" title="Active projects" aria-expanded="false" aria-controls="projects-submenu" data-submenu-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 7.5h16v13H4z"/><path d="M8 7.5V5h8v2.5M4 12h16"/></svg>
                <span class="admin-nav-text">Active projects</span> <span class="badge rounded-pill ms-auto text-dark" style="background: var(--app-green)">8</span>
                <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </button>
        <div id="projects-submenu" class="admin-submenu" hidden>
            <a class="admin-submenu-link" href="#delivery-tracker-title">In delivery</a>
            <a class="admin-submenu-link" href="#delivery-tracker-title">Upcoming milestones</a>
        </div>
        <button type="button" class="admin-nav-link admin-submenu-toggle" aria-label="Clients submenu" title="Clients" aria-expanded="false" aria-controls="clients-submenu" data-submenu-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="9" cy="8" r="3.5"/><path d="M3 20v-1.5A4.5 4.5 0 0 1 7.5 14h3a4.5 4.5 0 0 1 4.5 4.5V20M16 4.8a3.5 3.5 0 0 1 0 6.4m2 3a4.5 4.5 0 0 1 3 4.3V20"/></svg>
                <span class="admin-nav-text">Clients</span>
                <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </button>
        <div id="clients-submenu" class="admin-submenu" hidden>
            <a class="admin-submenu-link" href="#connected-fleet-title">Client deployments</a>
            <a class="admin-submenu-link" href="#connected-fleet-title">Connected devices</a>
        </div>
        <button type="button" class="admin-nav-link admin-submenu-toggle" aria-label="Project reports submenu" title="Project reports" aria-expanded="false" aria-controls="reports-submenu" data-submenu-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg>
                <span class="admin-nav-text">Project reports</span>
                <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </button>
        <div id="reports-submenu" class="admin-submenu" hidden>
            <a class="admin-submenu-link" href="#project-portfolio-title">Project portfolio</a>
            <a class="admin-submenu-link" href="#delivery-tracker-title">Delivery tracker</a>
        </div>
    </nav>

    <div class="admin-nav-label">Operations</div>
    <nav class="d-flex flex-column gap-1" aria-label="Store navigation">
            <button type="button" class="admin-nav-link admin-submenu-toggle" aria-label="IoT devices submenu" title="IoT devices" aria-expanded="false" aria-controls="iot-submenu" data-submenu-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/></svg>
                    <span class="admin-nav-text">IoT devices</span>
                    <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div id="iot-submenu" class="admin-submenu" hidden>
                <a class="admin-submenu-link" href="#connected-fleet-title">Fleet health</a>
                <a class="admin-submenu-link" href="#connected-fleet-title">Device connectivity</a>
            </div>
            <button type="button" class="admin-nav-link admin-submenu-toggle" aria-label="Production systems submenu" title="Production systems" aria-expanded="false" aria-controls="production-submenu" data-submenu-toggle>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M8 3.7c2.4 2.5 3.5 5.3 3.5 8.3s-1.1 5.8-3.5 8.3M16 3.7c-2.4 2.5-3.5 5.3-3.5 8.3s1.1 5.8 3.5 8.3"/></svg>
                    <span class="admin-nav-text">Production systems</span>
                    <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div id="production-submenu" class="admin-submenu" hidden>
                <a class="admin-submenu-link" href="#delivery-tracker-title">Production software</a>
                <a class="admin-submenu-link" href="#connected-fleet-title">System monitoring</a>
            </div>
    </nav>

    <div class="admin-sidebar-note mt-auto">
        <div class="d-flex align-items-center gap-2 small fw-medium text-white">
            <span class="rounded-circle" style="background: var(--app-green); height: .5rem; width: .5rem"></span>
            Systems are looking good
        </div>
        <p class="mb-0 mt-2 small">Your connected systems and active projects are on track.</p>
    </div>
</aside>
