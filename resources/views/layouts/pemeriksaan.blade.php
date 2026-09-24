{{-- Rangka halaman Pemeriksaan: sidebar, header, banner pasien, tab. Isi tab ada di @yield('konten'). --}}
@php
    $poli = $poli ?? request()->segment(2) ?? 'umum';
    $rm = $rm ?? request()->segment(3) ?? '';
    $base = url('pemeriksaan/' . $poli . '/' . $rm);
    $tabAktif = $tabAktif ?? 'Diagnosa';
    // Ubah alamat tab sesuai route project Anda
    $tabs = [
        ['Assesment', $base . '/asesmen'],
        ['SOAP', $base . '/soap'],
        ['Diagnosa', $base . '/diagnosa'],
        ['Resep', $base . '/resep'],
        ['Clinical Phatway', $base . '/clinical-pathway'],
        ['Penunjang', $base . '/penunjang'],
        ['Rujukan', $base . '/rujukan'],
        ['autentikasi', $base . '/autentikasi'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemeriksaan - Klinik Merah Putih</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <style>
        *{box-sizing:border-box}
        html,body{height:100%}
        body{margin:0;display:flex;font-family:'Poppins',system-ui,sans-serif;font-size:14px;color:#111;background:#fff}
        a{color:inherit;text-decoration:none}

        .sb{width:250px;flex-shrink:0;height:100vh;background:linear-gradient(180deg,#7a0000 0%,#420000 45%,#2a0000 100%);color:#fff;display:flex;flex-direction:column;padding:26px 22px 28px}
        .sb-brand{display:flex;align-items:center;gap:12px;padding:0 4px 22px;border-bottom:1px solid rgba(255,255,255,.35);margin-bottom:38px}
        .sb-logo{width:46px;height:46px;border-radius:10px;background:linear-gradient(180deg,#c21b1b,#7d0808);display:flex;align-items:center;justify-content:center}
        .sb-brand span{font-size:11px}
        .sb a{display:flex;align-items:center;gap:14px;padding:11px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:8px}
        .sb a.sub{padding-left:52px}
        .sb a.active{background:#b01515}
        .sb-space{flex:1}
        .sb-out{border-top:1px solid rgba(255,255,255,.35);padding-top:20px}

        .main{flex:1;min-width:0;height:100vh;display:flex;flex-direction:column;overflow-y:auto}
        .top{height:66px;flex-shrink:0;display:flex;align-items:center;justify-content:space-between;padding:0 34px;border-bottom:3px solid #e3e3e3;box-shadow:0 2px 3px rgba(0,0,0,.08)}
        .top-title{display:flex;align-items:center;gap:10px;color:#c00000;font-weight:700;font-size:15px}
        .top-admin{display:flex;align-items:center;gap:10px;color:#c00000;font-weight:700;font-size:14px}
        .content{padding:22px 34px 40px}
        .back{display:flex;align-items:center;gap:14px;font-size:13px;font-weight:600;margin:0 0 18px 4px}

        .pasien{display:flex;align-items:center;gap:28px;border-radius:14px;background:linear-gradient(90deg,#a81c1c 0%,#8a1212 55%,#5a0808 100%);color:#fff;padding:20px 26px}
        .pasien-nama{width:210px;flex-shrink:0}
        .pasien-nama small{display:block;font-size:12px;font-weight:600}
        .pasien-nama strong{display:block;font-size:22px;font-weight:700;letter-spacing:.2px}
        .pasien-grid{flex:1;display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:22px}
        .pasien-grid div{text-align:center;font-size:12px;font-weight:600}
        .pasien-grid span{display:block;height:24px;margin-top:6px;background:#fff;border-radius:9px;color:#333;font-weight:500;line-height:24px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:0 8px}

        .tabs{display:flex;align-items:center;justify-content:space-between;margin:18px 4px 14px;font-size:12px;font-weight:600}
        .tabs a{padding:7px 20px}
        .tabs a.on{background:linear-gradient(90deg,#b01212,#600000);color:#fff;border-radius:999px}

        .flash{margin:0 0 14px;padding:12px 16px;border-radius:8px;font-size:13px;background:#e8f7ea;color:#14602a;border:1px solid #b8e2c0}
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sb">
        <div class="sb-brand">
            <div class="sb-logo">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M12 21s-7-4.6-9.2-9.1C1.3 8.6 3.2 5 6.6 5c2 0 3.7 1.1 5.4 3.1C13.7 6.1 15.4 5 17.4 5c3.4 0 5.3 3.6 3.8 6.9C19 16.4 12 21 12 21z"/></svg>
            </div>
            <span>Klinik Merah Putih</span>
        </div>

        <a href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M12 3 2 12h3v8h5v-5h4v5h5v-8h3z"/></svg>Dashboard</a>
        <a href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path fill-rule="evenodd" d="M9 2h6v2h3a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3V2zm1 1.5V5h4V3.5h-4zM8 11h8v1.6H8V11zm0 3.5h8V16H8v-1.5z"/></svg>Pendaftaran</a>
        <a href="#" class="active"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8.5c-1-1.6-3.6-1.4-3.6.7 0 1.6 2 2.7 3.6 4 1.6-1.3 3.6-2.4 3.6-4 0-2.1-2.6-2.3-3.6-.7z"/><path d="M3 15h3.5l3 2h4.5a1.6 1.6 0 0 0 0-3.2H11M6.5 15v5H3M14 17l5-1.8a1.6 1.6 0 0 1 1.5 2.8L14 21l-4-1"/></svg>Pemeriksaan</a>
        <a href="#" class="sub">Penunjang</a>
        <a href="#" class="sub">Farmasi</a>
        <a href="#" class="sub">Rekam Medis</a>
        <a href="#" class="sub">Laporan</a>

        <div class="sb-space"></div>
        <div class="sb-out"><a href="#" class="sub" style="padding-left:14px;margin:0">Keluar</a></div>
    </aside>

    <div class="main">
        <header class="top">
            <div class="top-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#c00000"><path d="M12 3 2 12h3v8h5v-5h4v5h5v-8h3z"/></svg>
                Pemeriksaan
            </div>
            <div class="top-admin">
                <svg width="26" height="26" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#c00000"/><circle cx="12" cy="9.5" r="3.2" fill="#fff"/><path d="M5.5 19c1.2-3.2 3.6-4.6 6.5-4.6s5.3 1.4 6.5 4.6a10 10 0 0 1-13 0z" fill="#fff"/></svg>
                Admin
            </div>
        </header>

        <div class="content">
            <a href="{{ url('pemeriksaan/' . $poli) }}" class="back"><span>&larr;</span>Kembali ke Antrian</a>

            <section class="pasien">
                <div class="pasien-nama">
                    <small>Identitas Pasien</small>
                    <strong>{{ strtoupper($pasien->nama ?? 'Budi Santoso') }}</strong>
                </div>
                <div class="pasien-grid">
                    <div>No.RM<span>{{ $pasien->no_rm ?? $rm }}</span></div>
                    <div>NIK<span>{{ $pasien->nik ?? '' }}</span></div>
                    <div>Jenis Kelamin<span>{{ $pasien->jenis_kelamin ?? '' }}</span></div>
                    <div>Tanggal Lahir<span>{{ $pasien->tanggal_lahir ?? '' }}</span></div>
                    <div>Usia<span>{{ $pasien->usia ?? '' }}</span></div>
                </div>
            </section>

            <nav class="tabs">
                @foreach ($tabs as [$nama, $link])
                    <a href="{{ $link }}" class="{{ $nama === $tabAktif ? 'on' : '' }}">{{ $nama }}</a>
                @endforeach
            </nav>

            @if (session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            @yield('konten')
        </div>
    </div>

    @stack('scripts')
</body>
</html>
