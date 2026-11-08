<aside id="admin-sidebar" class="admin-sidebar flex-shrink-0" aria-label="Main navigation">
    <a href="{{ route('admin.dashboard') }}" class="admin-brand d-flex align-items-center gap-3 px-2 pb-2">
        <span class="app-brand-mark">{{ $firstLetter }}</span>
        <span class="admin-brand-copy">
                <span class="d-block fs-5 fw-semibold">{{$business_name}}</span>
        </span>
    </a>

    <div class="admin-nav-label">Projects</div>
    <nav class="d-flex flex-column gap-1" aria-label="Main navigation">
        <a href="{{ route('admin.dashboard') }}" aria-label="Overview" title="Overview" aria-current="page" class="admin-nav-link active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
            <span class="admin-nav-text">Overview</span>
        </a>
        @canany(['Blog View', 'Blog Category View'])
            <div class="admin-nav-label mt-3">Blog</div>
            @can('Blog View')
                <a href="{{ route('admin.blog.index') }}" class="admin-nav-link {{ request()->routeIs('admin.blog.index', 'admin.blog.create', 'admin.blog.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                    <span class="admin-nav-text">Blogs</span>
                </a>
            @endcan
            @can('Blog Category View')
                <a href="{{ route('admin.blog.category.index') }}" class="admin-nav-link {{ request()->routeIs('admin.blog.category.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                    <span class="admin-nav-text">Blog categories</span>
                </a>
            @endcan
        @endcanany
        @canany(['FAQ View', 'FAQ Category View'])
            <div class="admin-nav-label mt-3">FAQs</div>
            @can('FAQ View')
                <a href="{{ route('admin.faq.index') }}" class="admin-nav-link {{ request()->routeIs('admin.faq.index', 'admin.faq.create', 'admin.faq.edit') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-question" aria-hidden="true"></i>
                    <span class="admin-nav-text">FAQs</span>
                </a>
            @endcan
            @can('FAQ Category View')
                <a href="{{ route('admin.faq.category.index') }}" class="admin-nav-link {{ request()->routeIs('admin.faq.category.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                    <span class="admin-nav-text">FAQ categories</span>
                </a>
            @endcan
        @endcanany

        @canany(['Client View', 'Employee View'])
            <div class="admin-nav-label mt-3">Team</div>
            @can('Client View')
                <a href="{{ route('admin.client.index') }}" class="admin-nav-link {{ request()->routeIs('admin.client.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-handshake" aria-hidden="true"></i>
                    <span class="admin-nav-text">Clients</span>
                </a>
            @endcan
            @can('Employee View')
                <a href="{{ route('admin.employee.index') }}" class="admin-nav-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users" aria-hidden="true"></i>
                    <span class="admin-nav-text">Employees</span>
                </a>
            @endcan
        @endcanany
        @canany(['Testimonial View', 'Case Study View'])
            <div class="admin-nav-label mt-3">Client stories</div>
            @can('Testimonial View')
                <a href="{{ route('admin.testimonial.index') }}" class="admin-nav-link {{ request()->routeIs('admin.testimonial.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-quote-left" aria-hidden="true"></i>
                    <span class="admin-nav-text">Testimonials</span>
                </a>
            @endcan
            @can('Case Study View')
                <a href="{{ route('admin.case-study.index') }}" class="admin-nav-link {{ request()->routeIs('admin.case-study.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
                    <span class="admin-nav-text">Case studies</span>
                </a>
            @endcan
        @endcanany

        @canany(['Role View', 'Permission View'])
            <div class="admin-nav-label mt-3">Roles & Permissions</div>
            @can('Role View')
                <a href="{{ route('admin.role.index') }}" class="admin-nav-link {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-tag" aria-hidden="true"></i>
                    <span class="admin-nav-text">Roles</span>
                </a>
            @endcan
            @can('Permission View')
                <a href="{{ route('admin.permission.index') }}" class="admin-nav-link {{ request()->routeIs('admin.permission.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-key" aria-hidden="true"></i>
                    <span class="admin-nav-text">Permissions</span>
                </a>
            @endcan
        @endcanany

        @canany(['Settings View', 'Settings Site', 'Settings Logo & Favicon', 'Settings Contact', 'Settings Social Media', 'Settings Smtp'])
            <button type="button" class="admin-nav-link admin-submenu-toggle {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}" aria-label="Settings submenu" title="Settings" aria-expanded="false" aria-controls="settings-submenu" data-submenu-toggle {{ request()->routeIs('admin.setting.*') ? 'data-submenu-active=true' : '' }}>
                <i class="fa-solid fa-gear" aria-hidden="true"></i>
                <span class="admin-nav-text">Settings</span>
                <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
            </button>
            <div id="settings-submenu" class="admin-submenu" hidden>
                @can('Settings View')
                    <a class="admin-submenu-link {{ request()->routeIs('admin.setting.index') ? 'active' : '' }}" href="{{ route('admin.setting.index') }}">Settings overview</a>
                @endcan
                @can('Settings Site')
                    <a class="admin-submenu-link {{ request()->route('section') === 'general' ? 'active' : '' }}" href="{{ route('admin.setting.edit', 'general') }}">General</a>
                @endcan
                @can('Settings Logo & Favicon')
                    <a class="admin-submenu-link {{ request()->route('section') === 'logos-favicon' ? 'active' : '' }}" href="{{ route('admin.setting.edit', 'logos-favicon') }}">Logo & favicon</a>
                @endcan
                @can('Settings Contact')
                    <a class="admin-submenu-link {{ request()->route('section') === 'contact' ? 'active' : '' }}" href="{{ route('admin.setting.edit', 'contact') }}">Contact</a>
                @endcan
                @can('Settings Social Media')
                    <a class="admin-submenu-link {{ request()->route('section') === 'social-media' ? 'active' : '' }}" href="{{ route('admin.setting.edit', 'social-media') }}">Social media</a>
                @endcan
                @can('Settings Smtp')
                    <a class="admin-submenu-link {{ request()->route('section') === 'smtp' ? 'active' : '' }}" href="{{ route('admin.setting.edit', 'smtp') }}">SMTP</a>
                @endcan
            </div>
        @endcanany


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
                <span class="admin-nav-text">Active projects</span> <span class="badge rounded-pill admin-sidebar-badge ms-auto">8</span>
                <svg class="admin-submenu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="m7 10 5 5 5-5"/></svg>
        </button>
        <div id="projects-submenu" class="admin-submenu" hidden>
            <a class="admin-submenu-link" href="#delivery-tracker-title">In delivery</a>
            <a class="admin-submenu-link" href="#delivery-tracker-title">Upcoming milestones</a>
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
</aside>
