<aside class="sidebar" id="sidebarUtama">

    <!-- Tombol buka/tutup sidebar, menempel di garis tepi sidebar -->
    <button type="button" id="sidebarToggleBtn" class="sidebar-toggle-btn" aria-label="Buka/tutup menu">
        <i class="bi bi-chevron-left"></i>
    </button>

    {{-- =========================
         LOGO
    ========================== --}}
    <div class="logo">

        <div class="logo-icon">
            <img src="{{ asset('images/logo-sistem.png') }}" alt="Logo SIP-REK">
        </div>

        <div class="logo-text">
            <strong>SIP-REK</strong>
            <span>Rekam Medis</span>
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
   onclick="event.preventDefault(); toggleSubmenuPemeriksaan(this);">

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
        <a href="{{ route('farmasi.index') }}"
           class="menu-item {{ request()->routeIs('farmasi.*') ? 'active' : '' }}">

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

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('formLogoutSidebar').submit();">

            <i class="bi bi-box-arrow-right"></i>

            <span>Keluar</span>

        </a>

    </div>

    {{-- Form tersembunyi supaya klik "Keluar" mengirim POST (logout) yang benar --}}
    <form id="formLogoutSidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</aside>


<style>

    /* =================================
       SIDEBAR
    ================================== */

.sidebar {
    width: 230px;
    min-width: 230px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    color: white;
    background: linear-gradient(180deg, #980000 0%, #570000 100%);
    z-index: 1000;
    overflow: visible;
    transition: width 0.3s ease, min-width 0.3s ease;
}

/* Mode ciut/minimize (icon-only), kayak referensi gambar 1 */
.sidebar.collapsed {
    width: 72px;
    min-width: 72px;
}

/* Sembunyikan teks & submenu saat ciut, sisakan icon saja */
.sidebar.collapsed .logo-text,
.sidebar.collapsed .menu-item span,
.sidebar.collapsed .submenu-arrow,
.sidebar.collapsed .menu-group,
.sidebar.collapsed .logout span {
    display: none;
}

.sidebar.collapsed .menu-item,
.sidebar.collapsed .logout a {
    justify-content: center;
    padding: 0;
}

.sidebar.collapsed .menu-item i,
.sidebar.collapsed .logout i {
    width: auto;
}

.sidebar.collapsed .logo {
    justify-content: center;
    padding: 12px;
}

.sidebar.collapsed .logo-icon {
    margin-right: 0;
}


    /* =================================
       TOMBOL TOGGLE (☰) & BACKDROP
    ================================== */

.sidebar-toggle-btn {
    position: absolute;
    top: 78px;                 /* sejajar vertikal dengan area logo */
    right: -14px;               /* nongol pas di garis tepi sidebar */
    transform: translateY(-50%);
    z-index: 1200;

    width: 28px;
    height: 28px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #ed1111;
    color: white;
    border: 2px solid white;
    border-radius: 50%;

    font-size: 13px;
    cursor: pointer;
    padding: 0;

    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    transition: background 0.2s ease, right 0.3s ease;
}

.sidebar-toggle-btn:hover {
    background: #980000;
}

/* Saat sidebar ciut, ikon dibalik jadi panah kanan lewat JS,
   posisinya tetap otomatis ikut tepi sidebar karena "right" relatif */

/* Konten mengikuti lebar sidebar, tetap interaktif (tidak ada overlay) */
.main {
    transition: margin-left 0.3s ease, width 0.3s ease;
}

body:not(.sidebar-collapsed) .main {
    margin-left: 230px !important;
    width: calc(100% - 230px) !important;
}

body.sidebar-collapsed .main {
    margin-left: 72px !important;
    width: calc(100% - 72px) !important;
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
        width: 52px;
        height: 52px;

        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;

        margin-right: 10px;

        border: 2px solid white;
        border-radius: 10px;

        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.5px;

        overflow: hidden;
    }

    .logo-icon img {
        width: 100%;
        height: 100%;

        object-fit: contain;
        padding: 2px;
        box-sizing: border-box;
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

<script>
    // Klik menu yang punya submenu (mis. "Pemeriksaan").
    // Kalau sidebar sedang ciut, buka dulu sidebarnya, baru expand submenu.
    // Kalau sidebar sudah terbuka, langsung toggle submenu seperti biasa.
    function toggleSubmenuPemeriksaan(elMenuItem) {
        var sidebar = document.getElementById('sidebarUtama');
        var submenu = elMenuItem.nextElementSibling;

        if (sidebar.classList.contains('collapsed')) {
            // Buka sidebar dulu
            sidebar.classList.remove('collapsed');
            document.body.classList.remove('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', 'false');

            // Samakan ikon tombol toggle (<) jadi panah "tutup"
            var iconToggle = document.querySelector('#sidebarToggleBtn i');
            if (iconToggle) {
                iconToggle.className = 'bi bi-chevron-left';
            }

            // Langsung buka submenu-nya juga, tidak perlu diklik dua kali
            submenu.classList.add('open');
        } else {
            // Sidebar sudah terbuka -> perilaku normal, toggle buka/tutup submenu
            submenu.classList.toggle('open');
        }
    }

    (function () {
        var toggleBtn = document.getElementById('sidebarToggleBtn');
        var sidebar   = document.getElementById('sidebarUtama');
        var STORAGE_KEY = 'sidebarCollapsed';

        var isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
        var iconToggle = toggleBtn.querySelector('i');

        function terapkanStatus() {
            sidebar.classList.toggle('collapsed', isCollapsed);
            document.body.classList.toggle('sidebar-collapsed', isCollapsed);

            iconToggle.className = isCollapsed
                ? 'bi bi-chevron-right'
                : 'bi bi-chevron-left';
        }

        terapkanStatus();

        toggleBtn.addEventListener('click', function () {
            isCollapsed = !isCollapsed;
            localStorage.setItem(STORAGE_KEY, isCollapsed ? 'true' : 'false');
            terapkanStatus();
        });
    })();
</script>

<script>
    (function () {
        var toggleBtn = document.getElementById('sidebarToggleBtn');
        var sidebar   = document.getElementById('sidebarUtama');
        var STORAGE_KEY = 'sidebarCollapsed';

        // Default: sidebar TERBUKA (mis. baru login & belum pernah toggle).
        // Kalau sudah pernah di-toggle sebelumnya, status itu yang dipakai lagi.
        var isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';

var iconToggle = toggleBtn.querySelector('i');

function terapkanStatus() {
    sidebar.classList.toggle('collapsed', isCollapsed);
    document.body.classList.toggle('sidebar-collapsed', isCollapsed);

    // Panah menunjuk ke arah "buka": kalau ciut → panah kanan (buka),
    // kalau terbuka → panah kiri (tutup)
    iconToggle.className = isCollapsed
        ? 'bi bi-chevron-right'
        : 'bi bi-chevron-left';
}

        terapkanStatus();

        toggleBtn.addEventListener('click', function () {
            isCollapsed = !isCollapsed;
            localStorage.setItem(STORAGE_KEY, isCollapsed ? 'true' : 'false');
            terapkanStatus();
        });
    })();
</script>