<!-- Tombol buka/tutup sidebar (selalu terlihat, pojok kiri atas) -->
<button type="button" id="sidebarToggleBtn" class="sidebar-toggle-btn" aria-label="Buka/tutup menu">
    <i class="bi bi-list"></i>
</button>

<!-- Lapisan gelap di belakang sidebar saat terbuka, klik = menutup sidebar -->
<div id="sidebarBackdrop" class="sidebar-backdrop"></div>

<aside class="sidebar" id="sidebarUtama">

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

        /* Default: tersembunyi, digeser ke luar layar di sebelah kiri */
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    /* Cegah browser menangkap geser horizontal sebagai "swipe back"
       (kembali ke halaman sebelumnya), supaya gesture geser kanan/kiri
       bisa dipakai khusus untuk buka/tutup sidebar. */
    html, body {
        overscroll-behavior-x: none;
        touch-action: pan-y;
    }

    /* Saat class "sidebar-open" ditambahkan (lewat tombol toggle),
       sidebar digeser masuk ke kanan sampai terlihat penuh. */
    .sidebar.sidebar-open {
        transform: translateX(0);
        box-shadow: 4px 0 18px rgba(0, 0, 0, 0.25);
    }


    /* =================================
       TOMBOL TOGGLE (☰) & BACKDROP
    ================================== */

    .sidebar-toggle-btn {
        position: fixed;
        top: 16px;
        left: 16px;
        z-index: 1200;

        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #980000;
        color: white;
        border: none;
        border-radius: 10px;

        font-size: 20px;
        cursor: pointer;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        transition: background 0.2s ease, left 0.3s ease;
    }

    .sidebar-toggle-btn:hover {
        background: #ed1111;
    }

    /* Tombol ikut geser ke kanan (ke tepi sidebar) saat sidebar terbuka */
    body.sidebar-open .sidebar-toggle-btn {
        left: 244px;
    }

    .sidebar-backdrop {
        display: none;

        position: fixed;
        inset: 0;
        z-index: 999;

        background: rgba(0, 0, 0, 0.45);
    }

    /* Konten utama (.main) tetap disisakan sedikit ruang di kiri untuk
       tombol ☰ saat sidebar tertutup, supaya tombolnya tidak menimpa
       judul halaman. "!important" dipakai supaya menang walau tiap
       halaman punya aturan .main / margin-left sendiri-sendiri. */
    body:not(.sidebar-open) .main {
        margin-left: 62px !important;
        width: calc(100% - 62px) !important;
    }

    body.sidebar-open .sidebar-backdrop {
        display: block;
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
    // Toggle sidebar: buka/tutup lewat tombol ☰, atau tutup dengan klik
    // area gelap (backdrop) di luar sidebar.
    (function () {
        var toggleBtn = document.getElementById('sidebarToggleBtn');
        var sidebar   = document.getElementById('sidebarUtama');
        var backdrop  = document.getElementById('sidebarBackdrop');

        function bukaSidebar() {
            sidebar.classList.add('sidebar-open');
            document.body.classList.add('sidebar-open');
        }

        function tutupSidebar() {
            sidebar.classList.remove('sidebar-open');
            document.body.classList.remove('sidebar-open');
        }

        toggleBtn.addEventListener('click', function () {
            if (sidebar.classList.contains('sidebar-open')) {
                tutupSidebar();
            } else {
                bukaSidebar();
            }
        });

        backdrop.addEventListener('click', tutupSidebar);

        // ================================================================
        // GESER JARI (SWIPE) UNTUK BUKA/TUTUP SIDEBAR DI LAYAR SENTUH
        // - Geser ke KANAN mulai dari tepi kiri layar -> BUKA sidebar
        // - Geser ke KIRI saat sidebar sedang terbuka -> TUTUP sidebar
        //
        // e.preventDefault() di touchmove sengaja dipanggil supaya
        // gesture ini TIDAK "dibajak" oleh browser sebagai perintah
        // "kembali ke halaman sebelumnya" (swipe-back bawaan browser),
        // yang tadinya bikin geser kanan malah pindah ke halaman login.
        // ================================================================
        var TEPI_LAYAR    = 30; // area pemicu di tepi kiri layar (px)
        var JARAK_MINIMAL = 50; // jarak geser minimal supaya dihitung swipe (px)

        var touchStartX = null;
        var touchStartY = null;
        var sedangGeserSidebar = false;

        document.addEventListener('touchstart', function (e) {
            var t = e.touches[0];
            touchStartX = t.clientX;
            touchStartY = t.clientY;

            // Gesture ini "diklaim" untuk sidebar hanya kalau: sidebar
            // sedang terbuka (supaya bisa ditutup dari mana saja), ATAU
            // sentuhan dimulai persis di tepi kiri layar (untuk membuka).
            sedangGeserSidebar = sidebar.classList.contains('sidebar-open')
                || touchStartX <= TEPI_LAYAR;
        }, { passive: true });

        document.addEventListener('touchmove', function (e) {
            if (!sedangGeserSidebar || touchStartX === null || !e.touches[0]) {
                return;
            }

            var t = e.touches[0];
            var deltaX = t.clientX - touchStartX;
            var deltaY = t.clientY - touchStartY;

            // Kalau gerakannya lebih vertikal daripada horizontal
            // (misal lagi scroll halaman ke bawah), biarkan saja.
            if (Math.abs(deltaY) > Math.abs(deltaX)) {
                return;
            }

            // Blokir gesture "swipe back" bawaan browser selama jari
            // bergerak horizontal di area yang diklaim untuk sidebar.
            e.preventDefault();
        }, { passive: false });

        document.addEventListener('touchend', function (e) {
            if (!sedangGeserSidebar || touchStartX === null || !e.changedTouches[0]) {
                touchStartX = null;
                sedangGeserSidebar = false;
                return;
            }

            var t = e.changedTouches[0];
            var deltaX = t.clientX - touchStartX;

            if (!sidebar.classList.contains('sidebar-open') && deltaX > JARAK_MINIMAL) {
                bukaSidebar();
            } else if (sidebar.classList.contains('sidebar-open') && deltaX < -JARAK_MINIMAL) {
                tutupSidebar();
            }

            touchStartX = null;
            touchStartY = null;
            sedangGeserSidebar = false;
        });

        // ================================================================
        // GESER PAKAI MOUSE (untuk pengujian di desktop/laptop):
        // klik-tahan di tepi kiri layar lalu tarik ke kanan = buka;
        // klik-tahan di mana saja lalu tarik ke kiri saat sidebar
        // terbuka = tutup. Logikanya sama persis seperti versi sentuhan
        // di atas, cuma sumber event-nya mouse.
        // ================================================================
        var mouseStartX = null;
        var mouseStartY = null;
        var sedangKlikTahan = false;
        var sedangGeserMouse = false;

        document.addEventListener('mousedown', function (e) {
            mouseStartX = e.clientX;
            mouseStartY = e.clientY;
            sedangKlikTahan = true;

            sedangGeserMouse = sidebar.classList.contains('sidebar-open')
                || mouseStartX <= TEPI_LAYAR;

            // Cegah teks halaman ikut ter-select saat menarik mouse.
            if (sedangGeserMouse) {
                e.preventDefault();
            }
        });

        document.addEventListener('mousemove', function (e) {
            if (!sedangKlikTahan || !sedangGeserMouse) {
                return;
            }

            var deltaX = e.clientX - mouseStartX;
            var deltaY = e.clientY - mouseStartY;

            if (Math.abs(deltaY) > Math.abs(deltaX)) {
                return;
            }

            e.preventDefault();
        });

        document.addEventListener('mouseup', function (e) {
            if (sedangKlikTahan && sedangGeserMouse && mouseStartX !== null) {
                var deltaX = e.clientX - mouseStartX;

                if (!sidebar.classList.contains('sidebar-open') && deltaX > JARAK_MINIMAL) {
                    bukaSidebar();
                } else if (sidebar.classList.contains('sidebar-open') && deltaX < -JARAK_MINIMAL) {
                    tutupSidebar();
                }
            }

            mouseStartX = null;
            mouseStartY = null;
            sedangKlikTahan = false;
            sedangGeserMouse = false;
        });
    })();
</script>