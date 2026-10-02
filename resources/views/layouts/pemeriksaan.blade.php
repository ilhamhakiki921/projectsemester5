{{-- Rangka halaman Pemeriksaan (disamakan dengan halaman Resep): sidebar, topbar, identitas pasien, tab. Isi tab ada di @yield('konten'). --}}
@php
    $tabAktif = $tabAktif ?? 'Diagnosa';
    $noRm = $pasien->no_rm ?? $rm;
    // Tombol kembali bisa diganti per halaman (bawaan: kembali ke Diagnosa)
    $backUrl   = $backUrl   ?? route('pemeriksaan.diagnosa', [$poli, $noRm]);
    $backLabel = $backLabel ?? 'Kembali ke Diagnosa';
    $lahir = !empty($pasien?->tanggal_lahir) ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $tabs = [
        ['Anamnesa', route('pemeriksaan.asesmen', [$poli, $noRm])],
        ['SOAP', route('pemeriksaan.soap', [$poli, $noRm])],
        ['Diagnosa', route('pemeriksaan.diagnosa', [$poli, $noRm])],
        ['Resep', route('pemeriksaan.resep', [$poli, $noRm])],
        ['Clinical Pathway', route('pemeriksaan.clinical-pathway', [$poli, $noRm])],
        ['Penunjang', route('penunjang.index', [$poli, $noRm])],
        ['Rujukan', route('rujukan.index', [$poli, $noRm])],
        ['Autentikasi', route('autentikasi.index', [$poli, $noRm])],
    ];

        // Tab ini hanya tampil di halaman Informed Consent sendiri (munculnya dipicu klik "Ya" di SOAP)
    if (\App\Models\RekamMedis::poliPunyaInformedConsent($poli) && $tabAktif === 'Informed Consent') {
        array_splice($tabs, 2, 0, [['Informed Consent', route('pemeriksaan.informed-consent', [$poli, $noRm])]]);
    }
@endphp
<!DOCTYPE html>
<html lang="id" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google" content="notranslate">
    <title>Pemeriksaan — {{ $pasien->nama ?? 'Pasien' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Poppins', sans-serif; background: #ffffff; color: #222; }

        .main { min-height: 100vh; background: #f5f5f5; }

        .content { width: 100%; padding: 25px 22px; }

        .back-link { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 14px; color: #444; text-decoration: none; font-size: 11px; font-weight: 500; }
        .back-link:hover { color: #ed1111; }

        .identity-bar { display: flex; align-items: center; flex-wrap: wrap; gap: 22px; padding: 16px 22px; margin-bottom: 16px; border-radius: 10px; background: linear-gradient(180deg, #980000 0%, #570000 100%); color: #ffffff; }
        .identity-name-block { min-width: 170px; }
        .identity-label-top { font-size: 9px; opacity: 0.8; margin-bottom: 3px; }
        .identity-name { font-size: 16px; font-weight: 700; letter-spacing: 0.3px; }
        .identity-field { display: flex; flex-direction: column; gap: 4px; }
        .identity-field label { font-size: 9px; opacity: 0.85; }
        .identity-field .value-box { min-width: 120px; height: 28px; display: flex; align-items: center; padding: 0 10px; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.35); border-radius: 5px; font-size: 10px; font-weight: 500; }

        .tabs { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
        .tab-item { height: 32px; display: inline-flex; align-items: center; padding: 0 16px; border-radius: 20px; font-size: 10px; font-weight: 600; text-decoration: none; color: #1a1a1a; background: transparent; }
        .tab-item.active { background: #7a0000; color: #ffffff; }
        .tab-item.disabled { cursor: not-allowed; }

        .flash { margin: 0 0 14px; padding: 12px 16px; border-radius: 8px; font-size: 12px; background: #e8f7ea; color: #14602a; border: 1px solid #b8e2c0; }
    </style>
    <style>
        /* Warna fokus kolom isian: merah (fallback untuk kolom yang belum punya aturan sendiri) */
        :where(input:not([type="checkbox"]):not([type="radio"]):not([type="range"]):not([type="color"]):not([type="file"]):not([type="button"]):not([type="submit"]):not([type="reset"]), select, textarea):focus {
            outline: none;
            border-color: #b40000;
            box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="dashboard">

    <x-sidebar />

    <main class="main">
        <x-topbar title="Pemeriksaan" icon="heart-pulse-fill" />

        <section class="content">

            <x-popup-alert />

            <div class="identity-bar">
                <div class="identity-name-block">
                    <div class="identity-label-top">Identitas Pasien</div>
                    <div class="identity-name">{{ strtoupper($pasien->nama ?? '-') }}</div>
                </div>
                <div class="identity-field">
                    <label>No.RM</label>
                    <div class="value-box">{{ $noRm }}</div>
                </div>
                <div class="identity-field">
                    <label>NIK</label>
                    <div class="value-box">{{ $pasien->nik ?? '-' }}</div>
                </div>
                <div class="identity-field">
                    <label>Jenis Kelamin</label>
                    <div class="value-box">{{ $pasien->jenis_kelamin ?? '-' }}</div>
                </div>
                <div class="identity-field">
                    <label>Tanggal Lahir</label>
                    <div class="value-box">{{ $lahir ? $lahir->format('d-m-Y') : '-' }}</div>
                </div>
                <div class="identity-field">
                    <label>Usia</label>
                    <div class="value-box">{{ $lahir ? $lahir->age . ' Tahun' : '-' }}</div>
                </div>
            </div>

            <div class="tabs">
                @foreach ($tabs as [$nama, $link])
                    @if ($nama === $tabAktif)
                        <span class="tab-item active">{{ $nama }}</span>
                    @elseif ($link)
                        <a href="{{ $link }}" class="tab-item">{{ $nama }}</a>
                    @else
                        <span class="tab-item disabled">{{ $nama }}</span>
                    @endif
                @endforeach
            </div>

            @if (session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            @yield('konten')

        </section>
    </main>

</div>

@stack('scripts')
</body>
</html>