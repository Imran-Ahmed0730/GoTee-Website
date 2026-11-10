<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((tooltipTriggerEl) => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    if (typeof toastr !== 'undefined') {
        toastr.options = {
            closeButton: true,
            newestOnTop: true,
            positionClass: 'toast-top-right',
            progressBar: true,
            timeOut: 5000,
        };

        $(function () {
            const toast = @json(session('toast'));
            const flashMessages = {
                success: @json(session('success')),
                error: @json(session('error')),
                warning: @json(session('warning')),
                info: @json(session('info')),
            };

            if (toast && toast.message && typeof toastr[toast.type] === 'function') {
                toastr[toast.type](toast.message);
                return;
            }

            Object.entries(flashMessages).some(([type, message]) => {
                if (message && typeof toastr[type] === 'function') {
                    toastr[type](message);
                    return true;
                }

                return false;
            });
        });
    }

    document.querySelectorAll('.btn-delete').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const form = button.closest('form');

            if (!form) {
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        // 1. Globally disable scroll-to-change value on ALL number inputs
        document.addEventListener('wheel', (event) => {
            if (document.activeElement.type === 'number') {
                event.preventDefault();
            }
        }, { passive: false }); // 'passive: false' allows us to block the default scroll action

        // 2. Find the element with the autofocus attribute
        const autoFocusEl = document.querySelector('input[autofocus], textarea[autofocus]');

        if (autoFocusEl) {
            // Focus the element without triggering a page jump/scroll shift
            autoFocusEl.focus({ preventScroll: true });

            // 3. Move cursor to the end safely based on type
            const supportedTypes = ['text', 'search', 'url', 'tel', 'password'];
            const isTextarea = autoFocusEl.tagName.toLowerCase() === 'textarea';
            const isSupportedInput = autoFocusEl.tagName.toLowerCase() === 'input' && supportedTypes.includes(autoFocusEl.type);

            if (isTextarea || isSupportedInput) {
                const length = autoFocusEl.value.length;
                autoFocusEl.setSelectionRange(length, length);
            } else {
                // Fallback value re-assignment for number/other inputs
                const val = autoFocusEl.value;
                autoFocusEl.value = '';
                autoFocusEl.value = val;
            }
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

    function setSubmenuExpanded(toggle, expanded) {
        const submenu = document.getElementById(toggle.getAttribute('aria-controls'));

        toggle.setAttribute('aria-expanded', String(expanded));
        submenu.hidden = !expanded;
    }

    function setSidebarExpanded(expanded) {
        adminShell.classList.toggle('admin-sidebar-open', expanded);
        sidebarToggle.setAttribute('aria-expanded', String(expanded));
        sidebarToggle.setAttribute('aria-label', expanded ? 'Collapse navigation' : 'Expand navigation');
        sidebarBackdrop.hidden = !expanded || desktopSidebar.matches;
        sidebar.setAttribute('aria-hidden', String(!expanded && !desktopSidebar.matches));

        document.querySelectorAll('[data-submenu-toggle]').forEach((toggle) => {
            setSubmenuExpanded(toggle, expanded && toggle.hasAttribute('data-submenu-active'));
        });
    }

    sidebarToggle.addEventListener('click', () => {
        setSidebarExpanded(!adminShell.classList.contains('admin-sidebar-open'));
    });

    sidebarBackdrop.addEventListener('click', () => setSidebarExpanded(false));

    document.querySelectorAll('[data-submenu-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            if (!adminShell.classList.contains('admin-sidebar-open')) {
                setSidebarExpanded(true);
                if (!toggle.hasAttribute('data-submenu-active')) {
                    setSubmenuExpanded(toggle, true);
                }

                return;
            }

            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            setSubmenuExpanded(toggle, !expanded);
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

    let sidebarScrollTimeout;
    sidebar.addEventListener('scroll', () => {
        sidebar.classList.add('is-scrolling');
        window.clearTimeout(sidebarScrollTimeout);
        sidebarScrollTimeout = window.setTimeout(() => {
            sidebar.classList.remove('is-scrolling');
        }, 700);
    }, { passive: true });
</script>

<script>
    $(document).ready(function () {
        // Bind the click event to the toggle switches
        $('.toggle-switch').on('change', function (e) {
            e.preventDefault();

            let switchElement = $(this); // The clicked switch
            let id = switchElement.data('id'); // Get the item ID from data-id
            let newStatus = switchElement.prop('checked') ? 1 : 0; // Determine new status
            let module = switchElement.data('module'); // Get the module from data-module

            // SweetAlert confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${newStatus ? 'activate' : 'deactivate'} this ${module}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let route = `/admin/${module}/status/change/${id}`

                    // Send AJAX request to update the status
                    $.ajax({
                        url: route, // Use the dynamically constructed route
                        type: 'GET',
                        success: function (response) {
                            if (response.success) {
                                toastr.success(`The ${module} status has been successfully updated.`);
                            } else {
                                toastr.error(`Failed to update the ${module} status.`);
                                // Optionally, revert the switch back to the original state
                                switchElement.prop('checked', !newStatus);
                            }
                        },
                        error: function (error) {
                            toastr.error('An error occurred while updating the status.');
                            switchElement.prop('checked', !newStatus); // Revert the switch
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    switchElement.prop('checked', !newStatus); // Revert the switch on cancel
                }
            });
        });
    });
</script>
