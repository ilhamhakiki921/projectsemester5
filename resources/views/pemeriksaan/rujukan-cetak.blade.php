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
    .kop{text-align:center;border-bottom:3px double #000;padding-bottom:8px;margin-bottom:18px}
    .kop h1{margin:0;font-size:20px;letter-spacing:1px}
    .kop p{margin:2px 0 0;font-size:12px}
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
        <h1>KLINIK UTAMA MERAH PUTIH</h1>
        <p>Instalasi Rawat Jalan</p>
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
    <div class="lbl">Terapi yang Telah Diberikan</div><div class="box">{{ $surat->terapi ?: '-' }}</div>

    <p style="margin-top:16px">Demikian surat rujukan ini dibuat, atas perhatian dan kerja samanya diucapkan terima kasih.</p>

    <div class="ttd">
        {{ $surat->tanggal_surat->format('d-m-Y') }}<br>Dokter/Petugas Perujuk,
        <div class="nm">{{ $surat->dokter_perujuk }}</div>
    </div>
</div>
</body>
</html>
