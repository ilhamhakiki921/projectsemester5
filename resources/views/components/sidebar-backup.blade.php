<aside class="sidebar">

    {{-- Logo --}}
    <div class="logo">

    <div class="logo-icon">
        <span>SR</span>
    </div>

    <div class="logo-text">
        <strong>Klinik</strong>
        <span>Merah Putih</span>
    </div>

    <button type="button" class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="bi bi-chevron-left"></i>
    </button>

</div>


    {{-- Menu --}}
    <nav class="menu">

        {{-- Dashboard --}}
        <a href="#"
           class="menu-item">
            <i class="bi bi-house-fill"></i>
            <span>Dashboard</span>
        </a>


        {{-- =========================
     PENDAFTARAN
========================== --}}

<a
    href="{{ route('pendaftaran') }}"
    class="menu-item {{ request()->routeIs('pendaftaran') || request()->routeIs('pasien.*') ? 'active' : '' }}"
>

    <i class="bi bi-clipboard-plus"></i>

    <span>Pendaftaran</span>

</a>


        {{-- =========================
             POLI
        ========================== --}}
        <a href="#" class="menu-item menu-poli">

            <span class="menu-left">

                <i class="bi bi-people-fill"></i>

                <span>Poli</span>

            </span>

            <i class="bi bi-chevron-down arrow"></i>

        </a>


        {{-- =========================
             RADIOLOGI
        ========================== --}}
        <a href="#" class="menu-item">

            <i class="bi bi-radioactive"></i>

            <span>Radiologi</span>

        </a>

    </nav>


    {{-- Logout --}}
    <div class="logout">

        <a href="#">

            <i class="bi bi-box-arrow-right"></i>

            <span>Keluar</span>

        </a>

    </div>

</aside>

<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('collapsed');
        document.body.classList.toggle('sidebar-collapsed');

        localStorage.setItem(
            'sidebarCollapsed',
            document.body.classList.contains('sidebar-collapsed') ? '1' : '0'
        );
    }

    (function () {
        if (localStorage.getItem('sidebarCollapsed') === '1') {
            document.querySelector('.sidebar').classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
        }
    })();
</script>

<style>

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

    overflow: hidden;

    transition: width 0.25s ease;
}


/* ================================= 
   SIDEBAR - COLLAPSED
================================== */

.sidebar.collapsed {
    width: 78px;
}

.sidebar.collapsed .logo {
    padding: 12px 0;
    justify-content: center;
}

.sidebar.collapsed .logo-icon {
    margin-right: 0;
}

.sidebar.collapsed .logo-text,
.sidebar.collapsed .menu-item span,
.sidebar.collapsed .submenu-arrow,
.sidebar.collapsed .menu-group,
.sidebar.collapsed .logout a span {
    display: none;
}

.sidebar.collapsed .menu-item {
    justify-content: center;
    padding: 0;
}

.sidebar.collapsed .menu-item i {
    width: auto;
    justify-content: center;
}

.sidebar.collapsed .logout a {
    justify-content: center;
    padding: 0;
}


/* ================================= 
   TOGGLE BUTTON
================================== */

.sidebar-toggle {
    position: absolute;
    top: 26px;
    right: -12px;

    width: 24px;
    height: 24px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;
    border-radius: 50%;

    background: #ed1111;
    color: white;

    font-size: 11px;
    cursor: pointer;

    z-index: 1001;

    transition: transform 0.25s ease;
}

.sidebar.collapsed .sidebar-toggle {
    transform: rotate(180deg);
}


/* ================================= 
   MAIN CONTENT - RESPON KE COLLAPSE
================================== */

body.sidebar-collapsed .main {
    margin-left: 78px;
    width: calc(100% - 78px);
}

.main {
    transition: margin-left 0.25s ease, width 0.25s ease;
}

.logo {
    height: 75px;
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.18);
}

.logo-icon {
    width: 38px;
    height: 38px;
    border: 2px solid white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 9px;
    font-size: 17px;
}

.logo-text {
    font-size: 12px;
    font-weight: 600;
}

.menu {
    padding: 30px 12px;
}

.menu-item,
.menu-dropdown {
    width: 100%;
    height: 40px;
    display: flex;
    align-items: center;
    padding: 0 13px;
    margin-bottom: 6px;
    color: white;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 500;
    border: none;
    border-radius: 5px;
    background: transparent;
    cursor: pointer;
    transition: 0.2s;
}

.menu-item i,
.menu-dropdown i {
    width: 28px;
    font-size: 16px;
}

.menu-item:hover,
.menu-dropdown:hover {
    background: rgba(255,255,255,0.12);
}

.menu-group {
    width: 100%;
}

.menu-dropdown {
    justify-content: space-between;
}

.menu-left {
    display: flex;
    align-items: center;
}

.menu-dropdown .arrow {
    width: auto;
    font-size: 11px;
    transition: transform 0.25s ease;
}

.menu-item.active {
    background: #ed1111;
}

.menu-dropdown .arrow.rotate {
    transform: rotate(180deg);
}

.submenu {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.3s ease, opacity 0.2s ease;
    margin-bottom: 5px;
}

.submenu.show {
    max-height: 150px;
    opacity: 1;
}

.submenu a {
    display: flex;
    align-items: center;
    min-height: 34px;
    margin: 2px 0 2px 28px;
    padding: 7px 10px;
    color: rgba(255,255,255,0.82);
    text-decoration: none;
    font-size: 10px;
    font-weight: 400;
    border-radius: 4px;
    transition: 0.2s;
}

.submenu a:hover {
    color: white;
    background: rgba(255,255,255,0.12);
}

.submenu a.submenu-active {
    color: white;
    background: rgba(255,255,255,0.18);
    font-weight: 600;
}

.logout {
    margin-top: auto;
    padding: 18px 12px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.logout a {
    height: 40px;
    display: flex;
    align-items: center;
    padding: 0 13px;
    color: white;
    text-decoration: none;
    font-size: 12px;
}

.logout i {
    width: 28px;
    font-size: 17px;
}

</style>