<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    toastr.options = {
        closeButton: true,
        newestOnTop: true,
        positionClass: 'toast-top-right',
        progressBar: true,
        timeOut: 5000,
    };

    $(function () {
        const toast = @json(session('toast'));

        if (toast && toast.message && typeof toastr[toast.type] === 'function') {
            toastr[toast.type](toast.message);
        }
    });

</script>

<script>
    const adminShell = document.querySelector('.admin-shell');
    const sidebarToggle = document.querySelector('.admin-menu-toggle');
    const sidebarBackdrop = document.querySelector('.admin-sidebar-backdrop');
    const sidebar = document.querySelector('#admin-sidebar');
    const profileToggle = document.querySelector('.admin-profile-trigger');
    const profileMenu = document.querySelector('#admin-profile-menu');
    const desktopSidebar = window.matchMedia('(min-width: 992px)');

    function setSidebarExpanded(expanded) {
        adminShell.classList.toggle('admin-sidebar-open', expanded);
        sidebarToggle.setAttribute('aria-expanded', String(expanded));
        sidebarToggle.setAttribute('aria-label', expanded ? 'Collapse navigation' : 'Expand navigation');
        sidebarBackdrop.hidden = !expanded || desktopSidebar.matches;
        sidebar.setAttribute('aria-hidden', String(!expanded && !desktopSidebar.matches));

        if (!expanded) {
            document.querySelectorAll('[data-submenu-toggle]').forEach((toggle) => {
                toggle.setAttribute('aria-expanded', 'false');
                document.getElementById(toggle.getAttribute('aria-controls')).hidden = true;
            });
        }
    }

    sidebarToggle.addEventListener('click', () => {
        setSidebarExpanded(!adminShell.classList.contains('admin-sidebar-open'));
    });

    sidebarBackdrop.addEventListener('click', () => setSidebarExpanded(false));

    document.querySelectorAll('[data-submenu-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            if (!adminShell.classList.contains('admin-sidebar-open')) {
                setSidebarExpanded(true);
            }

            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!expanded));
            document.getElementById(toggle.getAttribute('aria-controls')).hidden = expanded;
        });
    });

    document.querySelectorAll('.admin-submenu-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (!desktopSidebar.matches) {
                setSidebarExpanded(false);
            }
        });
    });

    profileToggle.addEventListener('click', () => {
        const expanded = profileToggle.getAttribute('aria-expanded') === 'true';
        profileToggle.setAttribute('aria-expanded', String(!expanded));
        profileMenu.hidden = expanded;
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.admin-profile-trigger') && !event.target.closest('#admin-profile-menu')) {
            profileToggle.setAttribute('aria-expanded', 'false');
            profileMenu.hidden = true;
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarExpanded(false);
            profileToggle.setAttribute('aria-expanded', 'false');
            profileMenu.hidden = true;
        }
    });

    desktopSidebar.addEventListener('change', () => setSidebarExpanded(false));
    setSidebarExpanded(false);
</script>
