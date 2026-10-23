<aside class="sidebar" id="sidebarUtama">

    <!-- Watermark logo transparan di background, murni dekoratif -->
    <div class="sidebar-watermark" aria-hidden="true"></div>

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

            <span class="menu-icon"><i class="bi bi-grid-fill"></i></span>

            <span class="menu-label">Dashboard</span>

        </a>


        {{-- Pendaftaran --}}
        <a href="{{ route('pendaftaran') }}"
           class="menu-item {{ request()->routeIs('pendaftaran') || request()->routeIs('pasien.*') ? 'active' : '' }}">

            <span class="menu-icon"><i class="bi bi-clipboard-plus"></i></span>

            <span class="menu-label">Pendaftaran</span>

        </a>


        {{-- Pemeriksaan --}}
<a href="#"
   class="menu-item has-submenu {{ request()->routeIs('pemeriksaan.*') ? 'active' : '' }}"
   onclick="event.preventDefault(); toggleSubmenuPemeriksaan(this);">

    <span class="menu-icon"><i class="bi bi-heart-pulse-fill"></i></span>

    <span class="menu-label">Pemeriksaan</span>

    <i class="bi bi-chevron-down submenu-arrow"></i>

</a>

        <div class="menu-group {{ request()->routeIs('pemeriksaan.*') ? 'open' : '' }}">

            @php
                $poliSaatIni = request()->route('poli');
            @endphp
            @foreach (\App\Models\Poli::daftar(true) as $slugPoli => $namaPoliMenu)
                <a href="{{ route('pemeriksaan.index', $slugPoli) }}"
                   class="submenu-item {{ request()->routeIs('pemeriksaan.*') && ($poliSaatIni === $slugPoli || ($slugPoli === 'umum' && $poliSaatIni === null)) ? 'active' : '' }}">
                    {{ $namaPoliMenu }}
                </a>
            @endforeach

        </div>


        {{-- Penunjang --}}
        <a href="#"
           class="menu-item">

            <span class="menu-icon"><i class="bi bi-clipboard2-pulse-fill"></i></span>

            <span class="menu-label">Penunjang</span>

        </a>


        {{-- Farmasi --}}
        <a href="{{ route('farmasi.index') }}"
           class="menu-item {{ request()->routeIs('farmasi.*') ? 'active' : '' }}">

            <span class="menu-icon"><i class="bi bi-capsule"></i></span>

            <span class="menu-label">Farmasi</span>

        </a>


        {{-- Rekam Medis --}}
        <a href="{{ route('rekam-medis.index') }}"
           class="menu-item {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">

            <span class="menu-icon"><i class="bi bi-folder-fill"></i></span>

            <span class="menu-label">Rekam Medis</span>

        </a>


        {{-- Laporan (tampilannya belum dibuat; sementara belum mengarah ke halaman mana pun) --}}
        {{-- Nanti kalau halamannya sudah ada: ganti href="#" dengan {{ route('laporan.index') }}
             dan hapus onclick. Status "active" otomatis menyala untuk route berawalan laporan.* --}}
        <a href="#"
           class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}"
           onclick="event.preventDefault();">

            <span class="menu-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></span>

            <span class="menu-label">Laporan</span>

        </a>

    </nav>


    {{-- =========================
         LOGOUT
    ========================== --}}
    <div class="logout">

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('formLogoutSidebar').submit();">

            <span class="menu-icon"><i class="bi bi-box-arrow-right"></i></span>

            <span class="menu-label">Keluar</span>

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
    height: 100dvh;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    color: white;
    background: linear-gradient(160deg, #a30000 0%, #6e0000 55%, #4a0000 100%);
    z-index: 1000;
    overflow: visible;
    /* Sudut kanan atas & kanan bawah SENGAJA dibiarkan lancip/siku (0),
       jangan ditambah border-radius/clip-path diagonal di sini. */
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    transition: width 0.3s ease, min-width 0.3s ease;
}

/* Watermark logo transparan, murni dekoratif, tidak boleh menutupi klik */
.sidebar-watermark {
    position: absolute;
    left: -30px;
    bottom: -20px;
    width: 220px;
    height: 220px;
    background-image: url('{{ asset('images/logo-putih.png') }}');
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    opacity: 0.05;
    pointer-events: none;
    z-index: 0;
}

.sidebar.collapsed .sidebar-watermark {
    display: none;
}

/* Konten sidebar tampil di atas watermark */
.sidebar > .logo,
.sidebar > .menu,
.sidebar > .logout {
    position: relative;
    z-index: 1;
}

/* Mode ciut/minimize (icon-only), kayak referensi gambar 1 */
.sidebar.collapsed {
    width: 72px;
    min-width: 72px;
}

/* Sembunyikan teks & submenu saat ciut, sisakan icon saja */
.sidebar.collapsed .logo-text,
.sidebar.collapsed .menu-label,
.sidebar.collapsed .submenu-arrow,
.sidebar.collapsed .menu-group {
    display: none;
}

.sidebar.collapsed .menu-item,
.sidebar.collapsed .logout a {
    justify-content: center;
    padding: 0;
}

.sidebar.collapsed .menu-item .menu-icon,
.sidebar.collapsed .logout .menu-icon {
    margin-right: 0;
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
        flex-shrink: 0;

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

        border: 2px solid rgba(255, 255, 255, 0.85);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.06);

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
        /* Mengisi sisa tinggi di antara logo dan tombol Keluar, lalu bisa di-scroll */
        flex: 1 1 auto;
        min-height: 0;

        padding: 22px 12px 14px;

        overflow-y: auto;
        overflow-x: hidden;
        overscroll-behavior: contain;

        /* Scrollbar tipis, senada dengan sidebar */
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.35) transparent;
    }

    .menu::-webkit-scrollbar {
        width: 6px;
    }

    .menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .menu::-webkit-scrollbar-thumb {
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.3);
    }

    .menu::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    /* Mode ciut: tetap bisa di-scroll tapi tanpa scrollbar supaya ikon tidak terdorong */
    .sidebar.collapsed .menu {
        scrollbar-width: none;
    }

    .sidebar.collapsed .menu::-webkit-scrollbar {
        display: none;
    }


    .menu-item {
        width: 100%;
        height: 44px;
        flex-shrink: 0;

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

    .menu-icon {
        width: 30px;
        height: 30px;

        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;

        margin-right: 11px;

        border-radius: 8px;

        transition: background 0.2s ease, transform 0.15s ease;
    }

    .menu-item i {
        font-size: 16px;
    }


    /* =================================
       HOVER
    ================================== */

    .menu-item:hover {
        color: white;

        background: rgba(255, 255, 255, 0.1);

        text-decoration: none;
    }


    .menu-item:hover .menu-icon {
        background: rgba(255, 255, 255, 0.12);
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
        max-height: 800px;
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

        background: linear-gradient(135deg, #ff4d4d 0%, #c00000 100%);

        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.28);
    }

    .menu-item.active .menu-icon {
        background: rgba(255, 255, 255, 0.22);
    }


    .menu-item.active:hover {
        background: linear-gradient(135deg, #ff4d4d 0%, #c00000 100%);
    }

    .menu-item.active:hover .menu-icon {
        background: rgba(255, 255, 255, 0.22);
        transform: none;
    }


    /* =================================
       LOGOUT
    ================================== */

    .logout {
        flex-shrink: 0;
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


    .logout .menu-icon {
        width: 30px;
        height: 30px;
    }

    .logout i {
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

{{--
    MODE BACA SAJA untuk akun Petugas Rekam Medis di luar halaman Rekam Medis.
    Ini hanya merapikan tampilan (tombol dimatikan); penjagaan yang sebenarnya
    ada di server (App\Http\Middleware\BatasiAksesRole).
--}}
@if (auth()->user()?->isRm() && ! request()->routeIs('rekam-medis.*'))
<style>
    .mode-baca-nonaktif { opacity: .45; cursor: not-allowed !important; pointer-events: none; }
    .mode-baca-sembunyi { display: none !important; }
    #bannerModeBaca {
        position: fixed; left: 50%; bottom: 14px; transform: translateX(-50%);
        z-index: 9998; padding: 8px 16px; border-radius: 999px;
        background: #fff8e1; border: 1px solid #f1d28a; color: #5a4300;
        font: 500 12px 'Poppins', sans-serif; box-shadow: 0 4px 14px rgba(0,0,0,.12);
    }
</style>
<div id="bannerModeBaca">Mode lihat saja — di luar halaman Rekam Medis tidak dapat mengubah atau mencetak.</div>
<script>
(function () {
    var PESAN = 'Di luar halaman Rekam Medis, akun ini hanya dapat melihat data.';
    var aksi  = /(simpan|hapus|edit|ubah|tambah|delete|submit|import|upload|unggah|kirim|selesai|batalkan|verifikasi)/i;

    function adalahLogout(el) {
        var f = el.closest ? el.closest('form') : null;
        return f && /\/logout/.test(f.getAttribute('action') || '');
    }

    function kunci() {
        // 1) Form yang bukan GET (simpan/ubah/hapus): matikan semua isiannya
        document.querySelectorAll('form').forEach(function (f) {
            var method = (f.getAttribute('method') || 'GET').toUpperCase();
            if (method === 'GET' || adalahLogout(f)) return;
            f.querySelectorAll('input, select, textarea, button').forEach(function (el) {
                if (el.type === 'hidden') return;
                el.disabled = true;
                el.classList.add('mode-baca-nonaktif');
            });
            f.addEventListener('submit', function (e) { e.preventDefault(); alert(PESAN); }, true);
        });

        // 2) Tombol aksi di luar form (modal, tombol Edit/Hapus/Tambah, dst.)
        document.querySelectorAll('button, a.btn, [role="button"]').forEach(function (el) {
            if (adalahLogout(el) || el.closest('#sidebarUtama') || el.closest('.berkas-tabs')) return;
            var teks = (el.textContent || '') + ' ' + el.className + ' ' + el.id;
            if (aksi.test(teks)) {
                el.classList.add('mode-baca-nonaktif');
                el.setAttribute('title', PESAN);
                el.setAttribute('aria-disabled', 'true');
            }
        });

        // 3) Tombol cetak / unduh disembunyikan
        document.querySelectorAll('a[href*="/cetak"], a[href*="/unduh"], [onclick*="print"]').forEach(function (el) {
            el.classList.add('mode-baca-sembunyi');
        });
        document.querySelectorAll('button, a').forEach(function (el) {
            if (el.closest('#sidebarUtama')) return;
            var teks = (el.textContent || '') + ' ' + el.className + ' ' + el.id;
            if (/(cetak|print|unduh|download)/i.test(teks) || el.querySelector('.bi-printer')) {
                el.classList.add('mode-baca-sembunyi');
            }
        });
    }

    // 4) Permintaan AJAX selain GET diblokir di browser juga
    var fetchAsli = window.fetch;
    if (fetchAsli) {
        window.fetch = function (url, opt) {
            var m = ((opt && opt.method) || 'GET').toUpperCase();
            if (m !== 'GET' && m !== 'HEAD' && !/\/logout/.test(String(url))) {
                alert(PESAN);
                return Promise.reject(new Error(PESAN));
            }
            return fetchAsli.apply(this, arguments);
        };
    }
    var bukaAsli = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function (method, url) {
        if (String(method).toUpperCase() !== 'GET' && !/\/logout/.test(String(url))) {
            alert(PESAN);
            throw new Error(PESAN);
        }
        return bukaAsli.apply(this, arguments);
    };

    // 5) Cetak lewat Ctrl+P / window.print() juga dimatikan
    window.print = function () { alert(PESAN); };
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && String(e.key).toLowerCase() === 'p') {
            e.preventDefault();
            alert(PESAN);
        }
    });

    document.addEventListener('DOMContentLoaded', kunci);
})();
</script>
@endif
