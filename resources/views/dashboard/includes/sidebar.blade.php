<aside class="sidebar" id="sidebar">

    {{-- SIDEBAR START --}}
    <div class="sidebar-start">

        {{-- SIDEBAR HEADER --}}
        <div class="sidebar-head">

            {{-- LOGO --}}
            <a href="{{ route('HalamanDashboard') }}" class="logo-wrapper" title="Home">

                <span class="sr-only">Home</span>
                <span class="icon logo" aria-hidden="true"></span>

                <div class="logo-text">
                    <span class="logo-title">GIS</span>
                    <span class="logo-subtitle">Dashboard</span>
                </div>

            </a>

            {{-- TOGGLE BUTTON DESKTOP --}}
            <button class="sidebar-toggle transparent-btn" id="sidebarToggle" type="button" title="Toggle Sidebar"
                aria-label="Toggle Sidebar" aria-expanded="true">

                <span class="sr-only">Toggle Menu</span>
                <span class="icon menu-toggle" aria-hidden="true"></span>

            </button>

        </div>


        {{-- SIDEBAR BODY --}}
        <div class="sidebar-body">

            <span class="system-menu__title">SYSTEM</span>

            <ul class="sidebar-body-menu">

                {{-- DASHBOARD --}}
                <li>
                    <a href="{{ route('HalamanDashboard') }}"
                        class="{{ request()->routeIs('HalamanDashboard') ? 'active' : '' }}" title="Dashboard">

                        <span class="icon setting" aria-hidden="true"></span>
                        <span class="menu-text">Dashboard</span>

                    </a>
                </li>


                {{-- MANAJEMEN SHP --}}
                <li>
                    <a href="#" title="Manajemen SHP">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">

                            <path fill-rule="evenodd"
                                d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0m3.041 9a1 1 0 0 0-.946.674l-.172.499a1 1 0 0 1-.403.515l-.803.517a1 1 0 0 0-.458.84v.455a1 1 0 0 0 1 1h.257c.192 0 .38.055.542.16l.762.491a7 7 0 0 0 2.139-4.581l-.315-.204a1 1 0 0 0-.977-.06l-.169.082a1 1 0 0 1-.742.051l-1.02-.33A1 1 0 0 0 11.205 9z" />

                        </svg>

                        <span class="menu-text">Manajemen SHP</span>

                    </a>
                </li>


                {{-- PETA GIS --}}
                <li>
                    <a href="{{ route('HalamanMap') }}" class="{{ request()->routeIs('HalamanMap') ? 'active' : '' }}"
                        title="Peta GIS">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">

                            <path fill-rule="evenodd"
                                d="M16 .5a.5.5 0 0 0-.598-.49L10.5.99 5.598.01a.5.5 0 0 0-.196 0l-5 1A.5.5 0 0 0 0 1.5v14a.5.5 0 0 0 .598.49l4.902-.98 4.902.98a.5.5 0 0 0 .196 0l5-1A.5.5 0 0 0 16 14.5zM5 14.09V1.11l.5-.1.5.1v12.98l-.402-.08a.5.5 0 0 0-.196 0zm5 .8V1.91l.402.08a.5.5 0 0 0 .196 0L11 1.91v12.98l-.5.1z" />

                        </svg>

                        <span class="menu-text">Peta GIS</span>

                    </a>
                </li>


                {{-- APLIKASI SURVEY --}}
                <li>
                    <a href="{{ route('HalamanISurvey') }}"
                        class="{{ request()->routeIs('HalamanISurvey') ? 'active' : '' }}" title="Aplikasi Survey">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">

                            <path
                                d="M7 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zM2 1a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm0 8a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />

                        </svg>

                        <span class="menu-text">Aplikasi Survey</span>

                    </a>
                </li>


                {{-- SETTINGS --}}
                <li>
                    <a href="#" title="Settings">

                        <span class="icon setting" aria-hidden="true"></span>
                        <span class="menu-text">Settings</span>

                    </a>
                </li>

            </ul>

        </div>

    </div>


    {{-- SIDEBAR FOOTER --}}
    <div class="sidebar-footer">

        <a href="#" class="sidebar-user" title="User Profile">

            <span class="sidebar-user-img">

                <picture>
                    <source srcset="{{ asset('assets/dashboard/img/avatar/avatar-illustrated-01.webp') }}"
                        type="image/webp">

                    <img src="{{ asset('assets/dashboard/img/avatar/avatar-illustrated-01.png') }}" alt="User">
                </picture>

            </span>

            <div class="sidebar-user-info">

                <span class="sidebar-user__title">
                    Nafisa Sh.
                </span>

                <span class="sidebar-user__subtitle">
                    Support Manager
                </span>

            </div>

        </a>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const pageFlex = document.querySelector('.page-flex');
            const sidebar = document.getElementById('sidebar');

            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileToggle = document.getElementById('mobileSidebarToggle');

            if (!pageFlex || !sidebar) {
                return;
            }

            const mobileScreen = window.matchMedia('(max-width: 768px)');

            /*
            |--------------------------------------------------------------------------
            | SET STATUS SIDEBAR
            |--------------------------------------------------------------------------
            */

            function setSidebarState(collapsed) {

                pageFlex.classList.toggle('sidebar-collapsed', collapsed);

                if (sidebarToggle) {
                    sidebarToggle.setAttribute(
                        'aria-expanded',
                        String(!collapsed)
                    );
                }

                if (mobileToggle) {
                    mobileToggle.setAttribute(
                        'aria-expanded',
                        String(!collapsed)
                    );
                }

            }


            /*
            |--------------------------------------------------------------------------
            | TOGGLER DESKTOP
            |--------------------------------------------------------------------------
            */

            if (sidebarToggle) {

                sidebarToggle.addEventListener('click', function() {

                    const isCollapsed =
                        pageFlex.classList.contains('sidebar-collapsed');

                    setSidebarState(!isCollapsed);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | TOGGLER MOBILE NAVBAR
            |--------------------------------------------------------------------------
            */

            if (mobileToggle) {

                mobileToggle.addEventListener('click', function() {

                    const isCollapsed =
                        pageFlex.classList.contains('sidebar-collapsed');

                    setSidebarState(!isCollapsed);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | KLIK OVERLAY UNTUK MENUTUP SIDEBAR MOBILE
            |--------------------------------------------------------------------------
            */

            pageFlex.addEventListener('click', function(event) {

                if (!mobileScreen.matches) {
                    return;
                }

                if (pageFlex.classList.contains('sidebar-collapsed')) {
                    return;
                }

                // Pseudo-element overlay menghasilkan target pageFlex
                if (event.target === pageFlex) {
                    setSidebarState(true);
                }

            });


            /*
            |--------------------------------------------------------------------------
            | KLIK MENU OTOMATIS MENUTUP SIDEBAR MOBILE
            |--------------------------------------------------------------------------
            */

            const sidebarLinks = document.querySelectorAll(
                '#sidebar .sidebar-body-menu a'
            );

            sidebarLinks.forEach(function(link) {

                link.addEventListener('click', function() {

                    if (mobileScreen.matches) {
                        setSidebarState(true);
                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | TUTUP DENGAN TOMBOL ESC
            |--------------------------------------------------------------------------
            */

            document.addEventListener('keydown', function(event) {

                if (
                    event.key === 'Escape' &&
                    mobileScreen.matches &&
                    !pageFlex.classList.contains('sidebar-collapsed')
                ) {
                    setSidebarState(true);
                }

            });


            /*
            |--------------------------------------------------------------------------
            | PERUBAHAN UKURAN LAYAR
            |--------------------------------------------------------------------------
            */

            function handleScreenChange(event) {

                if (event.matches) {
                    // Mobile: sidebar tertutup saat pertama kali masuk
                    setSidebarState(true);
                } else {
                    // Desktop: sidebar terbuka normal
                    setSidebarState(false);
                }

            }

            if (mobileScreen.addEventListener) {
                mobileScreen.addEventListener('change', handleScreenChange);
            } else {
                // Kompatibilitas browser lama
                mobileScreen.addListener(handleScreenChange);
            }


            /*
            |--------------------------------------------------------------------------
            | STATUS AWAL
            |--------------------------------------------------------------------------
            */

            setSidebarState(mobileScreen.matches);

        });
    </script>
</aside>
