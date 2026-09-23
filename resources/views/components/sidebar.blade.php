<aside class="sidebar">

    {{-- =========================
         LOGO
    ========================== --}}
    <div class="logo">

        <div class="logo-icon">
            <span>SR</span>
        </div>

        <div class="logo-text">
            <strong>Klinik</strong>
            <span>Merah Putih</span>
        </div>

    </div>


    {{-- =========================
         MENU
    ========================== --}}
    <nav class="menu">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">

            <i class="bi bi-grid-fill"></i>

            <span>Dashboard</span>

        </a>


        {{-- Pendaftaran --}}
        <a href="{{ route('pendaftaran') }}"
           class="menu-item {{ request()->routeIs('pendaftaran') || request()->routeIs('pasien.*') ? 'active' : '' }}">

            <i class="bi bi-clipboard-plus"></i>

            <span>Pendaftaran</span>

        </a>


        {{-- Pemeriksaan --}}
        <a href="#"
           class="menu-item has-submenu {{ request()->routeIs('pemeriksaan.*') ? 'active' : '' }}"
           onclick="event.preventDefault(); this.nextElementSibling.classList.toggle('open');">

            <i class="bi bi-heart-pulse-fill"></i>

            <span>Pemeriksaan</span>

            <i class="bi bi-chevron-down submenu-arrow"></i>

        </a>

        <div class="menu-group {{ request()->routeIs('pemeriksaan.*') ? 'open' : '' }}">

            <a href="{{ route('pemeriksaan.index', 'umum') }}"
               class="submenu-item {{ request()->routeIs('pemeriksaan.*') && (request()->route('poli') === 'umum' || request()->route('poli') === null) ? 'active' : '' }}">
                Poli Umum
            </a>

            <a href="{{ route('pemeriksaan.index', 'gigi') }}"
               class="submenu-item {{ request()->route('poli') === 'gigi' ? 'active' : '' }}">
                Poli Gigi
            </a>

            <a href="{{ route('pemeriksaan.index', 'dalam') }}"
               class="submenu-item {{ request()->route('poli') === 'dalam' ? 'active' : '' }}">
                Poli Dalam
            </a>

            <a href="{{ route('pemeriksaan.index', 'mikrobiologi') }}"
               class="submenu-item {{ request()->route('poli') === 'mikrobiologi' ? 'active' : '' }}">
                Mikrobiologi
            </a>

            <a href="{{ route('pemeriksaan.index', 'fisioterapi') }}"
               class="submenu-item {{ request()->route('poli') === 'fisioterapi' ? 'active' : '' }}">
                Fisioterapi
            </a>

        </div>


        {{-- Penunjang --}}
        <a href="#"
           class="menu-item">

            <i class="bi bi-clipboard2-pulse-fill"></i>

            <span>Penunjang</span>

        </a>


        {{-- Farmasi --}}
        <a href="#"
           class="menu-item">

            <i class="bi bi-capsule"></i>

            <span>Farmasi</span>

        </a>


        {{-- Rekam Medis --}}
        <a href="#"
           class="menu-item">

            <i class="bi bi-folder-fill"></i>

            <span>Rekam Medis</span>

        </a>

    </nav>


    {{-- =========================
         LOGOUT
    ========================== --}}
    <div class="logout">

        <a href="#">

            <i class="bi bi-box-arrow-right"></i>

            <span>Keluar</span>

        </a>

    </div>

</aside>


<style>

    /* =================================
       SIDEBAR
    ================================== */

    .sidebar {
        width: 230px;
        height: 100vh;

        position: fixed;
        left: 0;
        top: 0;

        display: flex;
        flex-direction: column;

        color: white;

        background: linear-gradient(
            180deg,
            #980000 0%,
            #570000 100%
        );

        z-index: 1000;
    }


    /* =================================
       LOGO
    ================================== */

    .logo {
        height: 78px;

        display: flex;
        align-items: center;

        padding: 12px 20px;

        border-bottom: 1px solid rgba(255, 255, 255, 0.18);
    }


    .logo-icon {
        width: 39px;
        height: 39px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 10px;

        border: 2px solid white;
        border-radius: 10px;

        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }


    .logo-text {
        display: flex;
        flex-direction: column;

        line-height: 1.25;

        font-family: 'Poppins', sans-serif;
    }


    .logo-text strong {
        font-size: 13px;
        font-weight: 700;
    }


    .logo-text span {
        font-size: 11px;
        font-weight: 400;

        opacity: 0.85;
    }


    /* =================================
       MENU
    ================================== */

    .menu {
        padding: 28px 12px;
    }


    .menu-item {
        width: 100%;
        height: 44px;

        display: flex;
        align-items: center;

        padding: 0 14px;
        margin-bottom: 7px;

        color: white;

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 500;

        border-radius: 7px;

        transition:
            background 0.2s ease,
            transform 0.15s ease;
    }


    /* =================================
       ICON
    ================================== */

    .menu-item i {
        width: 29px;

        font-size: 16px;

        display: flex;
        align-items: center;
        justify-content: flex-start;
    }


    /* =================================
       HOVER
    ================================== */

    .menu-item:hover {
        color: white;

        background: rgba(255, 255, 255, 0.12);

        text-decoration: none;
    }


    .menu-item:hover i {
        transform: translateX(1px);
    }


    /* =================================
       SUBMENU
    ================================== */

    .submenu-arrow {
        margin-left: auto;

        font-size: 10px !important;

        width: auto !important;

        transition: transform 0.2s ease;
    }

    .menu-group.open .submenu-arrow {
        transform: rotate(180deg);
    }

    .menu-group {
        max-height: 0;
        overflow: hidden;

        transition: max-height 0.25s ease;
    }

    .menu-group.open {
        max-height: 260px;
    }

    .submenu-item {
        display: block;

        padding: 9px 14px 9px 47px;
        margin-bottom: 3px;

        color: rgba(255, 255, 255, 0.75);

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 500;

        border-radius: 7px;

        transition: background 0.2s ease, color 0.2s ease;
    }

    .submenu-item:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);

        text-decoration: none;
    }

    .submenu-item.active {
        color: #ffffff;
        background: #ed1111;
    }


    /* =================================
       ACTIVE MENU
    ================================== */

    .menu-item.active {
        color: white;

        background: #ed1111;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }


    .menu-item.active:hover {
        background: #ed1111;
    }


    /* =================================
       LOGOUT
    ================================== */

    .logout {
        margin-top: auto;

        padding: 16px 12px;

        border-top: 1px solid rgba(255, 255, 255, 0.18);
    }


    .logout a {
        height: 44px;

        display: flex;
        align-items: center;

        padding: 0 14px;

        color: white;

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 500;

        border-radius: 7px;

        transition: background 0.2s ease;
    }


    .logout a:hover {
        color: white;

        background: rgba(255, 255, 255, 0.12);

        text-decoration: none;
    }


    .logout i {
        width: 29px;

        font-size: 17px;
    }

</style>