@props([
    'title' => 'Dashboard',
    'icon' => 'house-fill',
])

<header class="topbar" style="height: 68px !important; min-height: 68px !important; max-height: 68px !important;">

    <div class="topbar-title">
        <i class="bi bi-{{ $icon }}"></i>
        <span>{{ $title }}</span>
    </div>

    <div class="topbar-admin">
        <i class="bi bi-person-circle"></i>
        <span>Admin</span>
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
</style>