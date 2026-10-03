@php
    $internal = $surat->jenis === 'Internal';
    $lahir = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $tujuanPoli = $namaPoli[$surat->poli_tujuan] ?? $surat->poli_tujuan;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Rujukan — {{ $pasien->nama }}</title>
<style>
    *{box-sizing:border-box}
    body{font-family:'Times New Roman',serif;color:#000;margin:0;background:#eee}
    .page{width:210mm;min-height:297mm;margin:12px auto;background:#fff;padding:20mm 20mm 16mm}
    .kop{display:flex;align-items:center;gap:14px;border-bottom:4px double #000;padding-bottom:10px;margin-bottom:18px}
    .kop .logo{width:92px;flex-shrink:0}
    .kop .logo img,.kop .logo svg{display:block;width:100%;height:auto}
    .kop .teks{flex:1;text-align:center;padding-right:92px}
    .kop h1{margin:0 0 3px;font-size:22px;letter-spacing:.5px;color:#d0121b}
    .kop p{margin:1px 0 0;font-size:11px;color:#1a1a3a}
    h2{text-align:center;font-size:16px;text-decoration:underline;margin:0 0 4px}
    .no{text-align:center;font-size:12px;margin-bottom:18px}
    table.i{border-collapse:collapse;font-size:13px;margin-bottom:12px}
    table.i td{padding:2px 6px 2px 0;vertical-align:top}
    p{font-size:13px;line-height:1.5;margin:0 0 10px}
    .lbl{font-weight:bold;font-size:13px;margin:12px 0 3px}
    .box{white-space:pre-wrap;font-size:13px;line-height:1.5;min-height:22px}
    .ttd{margin:36px 0 0 auto;width:230px;text-align:center;font-size:13px}
    .ttd .nm{margin-top:64px;font-weight:bold;text-decoration:underline}
    .bar{position:fixed;top:10px;right:14px;display:flex;gap:8px}
    .bar button{border:0;border-radius:6px;padding:8px 16px;font:600 12px sans-serif;cursor:pointer;color:#fff;background:#1717d6}
    .bar button.x{background:#666}
    @media print{body{background:#fff}.page{margin:0;width:auto;min-height:0;padding:0}.bar{display:none}@page{size:A4;margin:18mm}}
</style>
</head>
<body>
<div class="bar"><button onclick="window.print()">Cetak</button><button class="x" onclick="window.close()">Tutup</button></div>

<div class="page">
    <div class="kop">
        <div class="logo">
            @if (file_exists(public_path('images/logo-klinik.png')))
                <img src="{{ asset('images/logo-klinik.png') }}" alt="Logo Klinik">
            @else
                <svg viewBox="0 0 120 64" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#d0121b" d="M2 62 L14 4 L34 4 L38 34 L58 4 L78 4 L66 62 L50 62 L58 20 L38 54 L30 54 L26 20 L18 62 Z"/>
                    <path fill="#d0121b" d="M70 62 L82 4 L106 4 C118 4 120 14 117 26 C113 40 102 44 90 44 L86 44 L83 62 Z M93 18 L90 32 C98 32 103 30 105 24 C107 18 103 18 99 18 Z"/>
                </svg>
            @endif
        </div>
        <div class="teks">
            <h1>KLINIK UTAMA MERAH PUTIH</h1>
            <p>Jl. Ronggowarsito No. 98 A Ngawi</p>
            <p>Telp. (0351) 748186 / 081313142833 Email : merahputihklinik@gmail.com</p>
            <p>Izin operasional : 12230024425350001</p>
        </div>
    </div>

    <h2>SURAT RUJUKAN {{ strtoupper($surat->jenis) }}</h2>
    <div class="no">No. RJK/{{ $surat->tanggal_surat->format('Ymd') }}/{{ str_pad($surat->id, 4, '0', STR_PAD_LEFT) }}</div>

    @if ($internal)
        <p>Kepada Yth.<br><b>Dokter {{ $tujuanPoli }}</b><br>Klinik Utama Merah Putih</p>
    @else
        <p>Kepada Yth.<br><b>{{ $surat->ditujukan_kepada }}</b><br>{{ $surat->nama_faskes }}</p>
    @endif

    <p>Mohon pemeriksaan dan penanganan lebih lanjut terhadap pasien:</p>
    <table class="i">
        <tr><td>Nama</td><td>:</td><td>{{ $pasien->nama }}</td></tr>
        <tr><td>No. RM</td><td>:</td><td>{{ $pasien->no_rm }}</td></tr>
        <tr><td>NIK</td><td>:</td><td>{{ $pasien->nik ?? '-' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $pasien->jenis_kelamin ?? '-' }}</td></tr>
        <tr><td>Tanggal Lahir / Usia</td><td>:</td><td>{{ $lahir ? $lahir->format('d-m-Y') . ' / ' . $lahir->age . ' tahun' : '-' }}</td></tr>
        @if ($internal)
        <tr><td>Jadwal Datang</td><td>:</td><td>{{ $surat->jadwal_datang->format('d-m-Y') }}</td></tr>
        @endif
    </table>

    <div class="lbl">Anamnesa</div><div class="box">{{ $surat->anamnesa ?: '-' }}</div>
    <div class="lbl">Diagnosa</div><div class="box">{{ $surat->diagnosa ?: '-' }}</div>
    <div class="lbl">Terapi yang Telah Diberikan</div><div class="box">{{ $surat->terapi ? str_replace(' | ', ', ', $surat->terapi) : '-' }}</div>

    <p style="margin-top:16px">Demikian surat rujukan ini dibuat, atas perhatian dan kerja samanya diucapkan terima kasih.</p>

    <div class="ttd">
        Ngawi, {{ $surat->tanggal_surat->locale('id')->translatedFormat('j F Y') }}<br>Dokter/Petugas Perujuk,
        <div class="nm">{{ $surat->dokter_perujuk }}</div>
    </div>
</div>
</body>
</html>
