@props([
    'title' => 'Dashboard',
    'icon' => 'house-fill',
])

<header class="topbar" style="height: 68px !important; min-height: 68px !important; max-height: 68px !important;">

    <div class="topbar-title">
        <i class="bi bi-{{ $icon }}"></i>
        <span>{{ $title }}</span>
    </div>

    <div class="topbar-admin-wrapper">
    <div class="topbar-admin" onclick="document.getElementById('adminDropdown').classList.toggle('show')">
        <i class="bi bi-person-circle"></i>
        <span>{{ Auth::check() ? Auth::user()->nama_lengkap : 'Admin' }}</span>
    </div>

    <div class="admin-dropdown" id="adminDropdown">
        <a href="#" class="dropdown-item">
            <i class="bi bi-person"></i> Profil Saya
        </a>

        <div class="dropdown-divider"></div>
        <div class="dropdown-label">DATA MASTER</div>

        <a href="{{ route('user.index') }}" class="dropdown-item">Data User</a>
        <a href="{{ route('poli.index') }}" class="dropdown-item">Data Poli</a>
        <a href="{{ route('diagnosa.index') }}" class="dropdown-item">Data Diagnosa/Penyakit</a>
        <a href="{{ route('obat.index') }}" class="dropdown-item">Data Obat</a>
        <a href="{{ route('data-pasien.index') }}" class="dropdown-item">Data Pasien</a>

        <div class="dropdown-divider"></div>

        <a href="#" class="dropdown-item">
            <i class="bi bi-gear"></i> Pengaturan
        </a>

        <div class="dropdown-divider"></div>

        <form action="{{ route('logout') }}" method="POST" class="dropdown-logout-form">
            @csrf
            <button type="submit" class="dropdown-item dropdown-logout-btn text-danger">
                <i class="bi bi-box-arrow-in-left"></i> Logout
            </button>
        </form>
    </div>
</div>
</header>

<style>
    .topbar {
        width: 100%;
        height: 68px !important;
        min-height: 68px !important;
        max-height: 68px !important;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 0 28px;
        margin: 0 !important;

        position: relative;

        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;

        box-sizing: border-box;
        flex-shrink: 0;
    }

    .topbar-title {
    display: flex;
    align-items: center;
    gap: 10px;

    font-size: 18px;
    font-weight: 600;
    color: #d00000;
}

    .topbar-title i {
    font-size: 18px;
    color: #d00000;
}

    .topbar-admin {
    display: flex;
    align-items: center;
    gap: 8px;

    font-size: 15px;
    font-weight: 500;
    color: #d00000;
}
    .topbar-admin i {
    font-size: 20px;
    color: #d00000;
}

    .topbar-red {
    background: #b40000 !important;
    border-bottom: 1px solid #b40000;
}

.topbar-red .topbar-title {
    color: #ffffff;
}

.topbar-red .topbar-title i {
    color: #ffffff;
}

.topbar-red .topbar-admin {
    color: #ffffff;
}

.topbar-red .topbar-admin i {
    color: #ffffff;
}

.topbar-admin-wrapper {
    position: relative;
}

.topbar-admin {
    cursor: pointer;
    user-select: none;
}

.admin-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;

    width: 190px;              
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;       
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);

    padding: 6px 0;            
    z-index: 2000;
}

.admin-dropdown.show {
    display: block;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 6px;                  

    padding: 7px 14px;         

    font-size: 12px;          
    font-weight: 500;
    color: #111827;

    text-decoration: none;
}

.dropdown-item:hover {
    background: #f3f4f6;
    color: #111827;
    text-decoration: none;
}

.dropdown-item.text-danger {
    color: #d00000;
}

.dropdown-item.text-danger:hover {
    background: #fdecec;
    color: #d00000;
}

.dropdown-logout-form {
    margin: 0;
}

.dropdown-logout-btn {
    width: 100%;
    background: none;
    border: none;
    font-family: inherit;
    text-align: left;
    cursor: pointer;
}

.dropdown-label {
    padding: 4px 14px 2px;    
    font-size: 10px;           
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #9ca3af;
}

.dropdown-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 4px 0;              
}
</style>
<script>
    document.addEventListener('click', function (e) {
        const wrapper = document.querySelector('.topbar-admin-wrapper');
        const dropdown = document.getElementById('adminDropdown');
        if (wrapper && !wrapper.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
</script>