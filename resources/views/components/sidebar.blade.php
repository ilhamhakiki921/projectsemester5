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
        <div class="menu-group">

            <button
                type="button"
                class="menu-dropdown {{ request()->routeIs('pemeriksaan.*') ? 'active' : '' }}"
                onclick="togglePemeriksaan()"
            >

                <span class="menu-left">
                    <i class="bi bi-heart-pulse-fill"></i>
                    <span>Pemeriksaan</span>
                </span>

                <i
                    id="pemeriksaan-arrow"
                    class="bi bi-chevron-down arrow {{ request()->routeIs('pemeriksaan.*') ? 'rotate' : '' }}"
                ></i>

            </button>

            <div
                id="pemeriksaan-submenu"
                class="submenu {{ request()->routeIs('pemeriksaan.*') ? 'show' : '' }}"
            >

                @foreach (['umum' => 'Poli Umum', 'gigi' => 'Poli Gigi', 'dalam' => 'Poli Dalam', 'mikrobiologi' => 'Mikrobiologi', 'fisioterapi' => 'Fisioterapi'] as $kode => $nama)

                    <a
                        href="{{ route('pemeriksaan.index', ['poli' => $kode]) }}"
                        class="{{ request()->routeIs('pemeriksaan.*') && request('poli', 'umum') === $kode ? 'submenu-active' : '' }}"
                    >
                        {{ $nama }}
                    </a>

                @endforeach

            </div>

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
        <a href="{{ route('rekam-medis.index') }}"
           class="menu-item {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">

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
       MENU DROPDOWN (misal: Pemeriksaan)
    ================================== */

    .menu-group {
        width: 100%;
    }

    .menu-dropdown {
        width: 100%;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 0 14px;
        margin-bottom: 7px;

        color: white;

        text-decoration: none;

        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 500;

        border: none;
        border-radius: 7px;

        background: transparent;

        cursor: pointer;

        transition:
            background 0.2s ease,
            transform 0.15s ease;
    }

    .menu-dropdown .menu-left {
        display: flex;
        align-items: center;
    }

    .menu-dropdown i {
        width: 29px;

        font-size: 16px;

        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .menu-dropdown .arrow {
        width: auto;

        font-size: 11px;

        transition: transform 0.25s ease;
    }

    .menu-dropdown .arrow.rotate {
        transform: rotate(180deg);
    }

    .menu-dropdown:hover {
        background: rgba(255, 255, 255, 0.12);
    }

    .menu-dropdown.active {
        background: #ed1111;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }


    /* =================================
       SUBMENU
    ================================== */

    .submenu {
        max-height: 0;

        overflow: hidden;

        opacity: 0;

        transition:
            max-height 0.3s ease,
            opacity 0.2s ease;

        margin-bottom: 5px;
    }

    .submenu.show {
        max-height: 220px;

        opacity: 1;
    }

    .submenu a {
        display: flex;
        align-items: center;

        min-height: 34px;

        margin: 2px 0 2px 28px;
        padding: 7px 10px;

        color: rgba(255, 255, 255, 0.82);

        text-decoration: none;

        font-size: 10px;
        font-weight: 400;

        border-radius: 4px;

        transition: 0.2s;
    }

    .submenu a:hover {
        color: white;

        background: rgba(255, 255, 255, 0.12);
    }

    .submenu a.submenu-active {
        color: white;
        font-weight: 600;

        background: rgba(255, 255, 255, 0.18);
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

<script>

function togglePemeriksaan() {

    const submenu = document.getElementById('pemeriksaan-submenu');
    const arrow = document.getElementById('pemeriksaan-arrow');

    submenu.classList.toggle('show');
    arrow.classList.toggle('rotate');

}

</script>