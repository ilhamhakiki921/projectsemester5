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

    <button
    type="button"
    class="sidebar-close"
    onclick="toggleSidebar()"
    title="Tutup menu"
>
    <i class="bi bi-chevron-left"></i>
</button>

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
<div class="menu-dropdown">

    <a href="{{ route('pemeriksaan') }}"
       class="menu-item pemeriksaan-toggle {{ request()->routeIs('pemeriksaan*') ? 'active' : '' }}"
       onclick="togglePemeriksaan(event)">
        <i class="bi bi-heart-pulse-fill"></i>
        <span>Pemeriksaan</span>
        <i class="bi bi-chevron-down dropdown-icon"></i>
    </a>

    <div class="submenu" id="submenuPemeriksaan">

        <a href="{{ route('pemeriksaan.umum') }}"
           class="submenu-item {{ request()->routeIs('pemeriksaan.umum') ? 'active' : '' }}">
            Poli Umum
        </a>

        <a href="{{ route('pemeriksaan.dalam') }}"
           class="submenu-item {{ request()->routeIs('pemeriksaan.dalam') ? 'active' : '' }}">
            Poli Dalam
        </a>

        <a href="{{ route('pemeriksaan.gigi') }}"
           class="submenu-item {{ request()->routeIs('pemeriksaan.gigi') ? 'active' : '' }}">
            Poli Gigi
        </a>

        <a href="{{ route('pemeriksaan.fisioterapi') }}"
           class="submenu-item {{ request()->routeIs('pemeriksaan.fisioterapi') ? 'active' : '' }}">
            Fisioterapi
        </a>

        <a href="{{ route('pemeriksaan.mikrobiologi') }}"
           class="submenu-item {{ request()->routeIs('pemeriksaan.mikrobiologi') ? 'active' : '' }}">
            Mikrobiologi
        </a>

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

    transition: height 0.25s ease;
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

    .sidebar-close {
    margin-left: auto;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;

    border: none;
    border-radius: 6px;

    background: transparent;
    color: white;

    font-size: 15px;
    cursor: pointer;

    transition: background 0.2s ease;
}

.sidebar-close:hover {
    background: rgba(255, 255, 255, 0.12);
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

    /* =================================
   PEMERIKSAAN DROPDOWN
================================= */

.menu-dropdown {
    width: 100%;
}

.pemeriksaan-toggle {
    position: relative;
}

.dropdown-icon {
    width: auto !important;
    margin-left: auto;
    font-size: 11px !important;
    transition: transform 0.2s ease;
}

.submenu {
    display: none;
    padding: 2px 0 6px 0;
}

/* =================================
   PEMERIKSAAN DROPDOWN
================================= */

.menu-dropdown {
    width: 100%;
}

.pemeriksaan-toggle {
    position: relative;
}

.dropdown-icon {
    width: auto !important;
    margin-left: auto;
    font-size: 11px !important;
    transition: transform 0.2s ease;
}

.submenu {
    display: none;
    padding: 2px 0 6px 0;
}

.submenu.show {
    display: block;
}

.dropdown-icon.rotate {
    transform: rotate(180deg);
}

.submenu-item {
    display: flex;
    align-items: center;
    height: 38px;
    margin: 3px 0 3px 14px;
    padding: 0 10px 0 29px;
    color: rgba(255, 255, 255, 0.82);
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-size: 11px;
    font-weight: 400;
    border-radius: 6px;
    transition: background 0.2s ease;
}

.submenu-item:hover {
    color: white;
    background: rgba(255, 255, 255, 0.12);
    text-decoration: none;
}

.submenu-item.active {
    color: white;
    background: rgba(255, 255, 255, 0.18);
    font-weight: 500;
}

.menu-dropdown:hover .dropdown-icon {
    transform: rotate(180deg);
}

.submenu-item {
    display: flex;
    align-items: center;
    height: 38px;
    margin: 3px 0 3px 14px;
    padding: 0 10px 0 29px;
    color: rgba(255, 255, 255, 0.82);
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-size: 11px;
    font-weight: 400;
    border-radius: 6px;
    transition: background 0.2s ease;
}

.submenu-item:hover {
    color: white;
    background: rgba(255, 255, 255, 0.12);
    text-decoration: none;
}

.submenu-item.active {
    color: white;
    background: rgba(255, 255, 255, 0.18);
    font-weight: 500;
}

/* =================================
   SIDEBAR COLLAPSE
================================= */

/* =================================
   SIDEBAR COLLAPSE
================================= */

.sidebar {
    width: 230px;
    height: 100vh;
    transition: transform 0.25s ease;
}

body.sidebar-collapsed .sidebar {
    transform: translateX(-162px);
}

body.sidebar-collapsed .sidebar-close i {
    transform: rotate(180deg);
}

</style>

<script>

    function toggleSidebar() {

        const body = document.body;
        const tombol = document.querySelector('.sidebar-close');

        if (body.classList.contains('sidebar-collapsed')) {

            body.classList.remove('sidebar-collapsed');

            if (tombol) {
                tombol.innerHTML = '<i class="bi bi-chevron-left"></i>';
                tombol.title = 'Tutup menu';
            }

        } else {

            body.classList.add('sidebar-collapsed');

            if (tombol) {
                tombol.innerHTML = '<i class="bi bi-chevron-right"></i>';
                tombol.title = 'Buka menu';
            }

        }

    }


    function togglePemeriksaan(event) {

        event.preventDefault();

        const submenu = document.getElementById('submenuPemeriksaan');
        const icon = document.querySelector('.dropdown-icon');

        submenu.classList.toggle('show');
        icon.classList.toggle('rotate');

        if (submenu.classList.contains('show')) {
            return;
        }

        window.location.href = "{{ route('pemeriksaan') }}";

    }


    document.addEventListener('DOMContentLoaded', function () {

        const submenu = document.getElementById('submenuPemeriksaan');
        const icon = document.querySelector('.dropdown-icon');

        @if(request()->routeIs('pemeriksaan*'))
            submenu.classList.add('show');
            icon.classList.add('rotate');
        @endif

    });

</script>