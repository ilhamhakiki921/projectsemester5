@php
    // Dipakai untuk tampilan cetak (browser) dan unduh PDF (DomPDF). Ukuran kertas: F4 (215 x 330 mm).
    // Tata letak memakai tabel, bukan flexbox, supaya hasil PDF sama dengan tampilan cetak.
    $pdf   = $pdf ?? false;
    $sakit = $surat->jenis === 'Sakit';
    $lahir = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $tgl   = fn ($d) => $d ? $d->locale('id')->translatedFormat('j F Y') : '-';

    // Logo disematkan sebagai data URI supaya tampil di PDF tanpa akses jaringan
    $svgLogo = '<svg viewBox="0 0 120 64" xmlns="http://www.w3.org/2000/svg"><path fill="#d0121b" d="M2 62 L14 4 L34 4 L38 34 L58 4 L78 4 L66 62 L50 62 L58 20 L38 54 L30 54 L26 20 L18 62 Z"/><path fill="#d0121b" d="M70 62 L82 4 L106 4 C118 4 120 14 117 26 C113 40 102 44 90 44 L86 44 L83 62 Z M93 18 L90 32 C98 32 103 30 105 24 C107 18 103 18 99 18 Z"/></svg>';
    $fileLogo = public_path('images/logo-klinik.png');
    // Ukuran tanda tangan: muat dalam 200 x 70 px dengan perbandingan asli, jadi pas di atas nama dokter
    $ttdW = $ttdH = null;
    if ($surat->ttd_dokter && preg_match('#^data:image/\w+;base64,(.+)$#s', $surat->ttd_dokter, $m)) {
        $info = @getimagesizefromstring(base64_decode($m[1]));
        if ($info && $info[0] > 0 && $info[1] > 0) {
            $k = min(200 / $info[0], 70 / $info[1]);
            $ttdW = (int) round($info[0] * $k);
            $ttdH = (int) round($info[1] * $k);
        }
    }

    $logo = file_exists($fileLogo)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($fileLogo))
        : 'data:image/svg+xml;base64,' . base64_encode($svgLogo);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Keterangan {{ $surat->jenis }} — {{ $pasien->nama }}</title>
<style>
    *{box-sizing:border-box}
    body{font-family:'Times New Roman',Times,serif;color:#000;margin:0;background:#eee}
    .page{width:215mm;min-height:330mm;margin:12px auto;background:#fff;padding:20mm 20mm 16mm}
    table{border-collapse:collapse}
    .kop{width:100%;border-bottom:4px double #000;margin-bottom:18px}
    .kop td{padding:0 0 10px;vertical-align:middle}
    .kop .logo{width:100px}
    .kop .logo img{display:block;width:92px;height:auto}
    .kop .teks{text-align:center;padding-right:100px}
    .kop h1{margin:0 0 3px;font-size:22px;letter-spacing:.5px;color:#d0121b}
    .kop h1{text-align:center}
    .kop p{margin:1px 0 0;font-size:11px;color:#1a1a3a;text-align:center}
    h2{text-align:center;font-size:16px;text-decoration:underline;margin:0 0 4px}
    .no{text-align:center;font-size:12px;margin-bottom:22px}
    p{font-size:13px;line-height:1.6;margin:0 0 10px;text-align:justify}
    table.i{font-size:13px;margin:0 0 14px 20px}
    table.i td{padding:2px 6px 2px 0;vertical-align:top}
    .ttd{margin:30px 0 0 auto;width:240px;text-align:center;font-size:13px;page-break-inside:avoid}
    .ttd .tgl{margin-bottom:6px}
    .ttd .peran{margin:0 0 4px}
    .ttd .gambar{height:84px;margin-bottom:10px;text-align:center}
    .ttd .gambar img{display:block;margin:6px auto 0;max-width:200px;max-height:70px}
    .ttd .nm{font-weight:bold;text-decoration:underline}
    .bar{position:fixed;top:10px;right:14px}
    .bar button{border:0;border-radius:6px;padding:8px 16px;margin-left:8px;font:600 12px sans-serif;cursor:pointer;color:#fff;background:#1717d6}
    .bar button.x{background:#666}
    @page{size:215mm 330mm;margin:18mm}
    @if ($pdf)
        body{background:#fff}.page{margin:0;width:auto;min-height:0;padding:0}
    @else
        @media print{body{background:#fff}.page{margin:0;width:auto;min-height:0;padding:0}.bar{display:none}}
    @endif
</style>
</head>
<body>
@unless ($pdf)
<div class="bar"><button onclick="window.print()">Cetak</button><button class="x" onclick="window.close()">Tutup</button></div>
@endunless

<div class="page">
    <table class="kop">
        <tr>
            <td class="logo"><img src="{{ $logo }}" alt="Logo Klinik"></td>
            <td class="teks">
                <h1>KLINIK UTAMA MERAH PUTIH</h1>
                <p>Jl. Ronggowarsito No. 98 A Ngawi</p>
                <p>Telp. (0351) 748186 / 081313142833 Email : merahputihklinik@gmail.com</p>
                <p>Izin operasional : 12230024425350001</p>
            </td>
        </tr>
    </table>

    <h2>SURAT KETERANGAN {{ strtoupper($surat->jenis) }}</h2>
    <div class="no">No. {{ $surat->nomor_surat }}</div>

    <p>Yang bertanda tangan di bawah ini, dokter pada Klinik Utama Merah Putih, menerangkan bahwa:</p>
    <table class="i">
        <tr><td>Nama</td><td>:</td><td>{{ $pasien->nama }}</td></tr>
        <tr><td>No. RM</td><td>:</td><td>{{ $pasien->no_rm }}</td></tr>
        <tr><td>Umur</td><td>:</td><td>{{ $lahir ? $lahir->age . ' tahun' : '-' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $pasien->jenis_kelamin ?? '-' }}</td></tr>
        <tr><td>Pekerjaan</td><td>:</td><td>{{ $pasien->pekerjaan ?: '-' }}</td></tr>
        <tr><td>Alamat</td><td>:</td><td>{{ $surat->alamat_pasien }}</td></tr>
    </table>

    @if ($sakit)
        <p>Berdasarkan hasil pemeriksaan yang telah dilakukan, yang bersangkutan dalam keadaan <b>sakit</b> dan memerlukan istirahat selama <b>{{ $surat->lama_istirahat }} ({{ \App\Models\SuratMedis::terbilang((int) $surat->lama_istirahat) }}) hari</b>, terhitung mulai tanggal <b>{{ $tgl($surat->mulai_tanggal) }}</b> sampai dengan tanggal <b>{{ $tgl($surat->sampai_tanggal) }}</b>.</p>
    @else
        <p>Berdasarkan hasil pemeriksaan kesehatan jasmani yang telah dilakukan pada tanggal <b>{{ $tgl($surat->tanggal_pemeriksaan ?? $surat->tanggal_surat) }}</b> dengan hasil sebagai berikut:</p>
        <table class="i">
            <tr><td>Berat Badan</td><td>:</td><td>{{ rtrim(rtrim(number_format((float) $surat->berat_badan, 1, ',', ''), '0'), ',') }} kg</td></tr>
            <tr><td>Tinggi Badan</td><td>:</td><td>{{ rtrim(rtrim(number_format((float) $surat->tinggi_badan, 1, ',', ''), '0'), ',') }} cm</td></tr>
            <tr><td>Tekanan Darah</td><td>:</td><td>{{ $surat->tekanan_darah }} mmHg</td></tr>
            <tr><td>Hasil Pemeriksaan</td><td>:</td><td>{{ $surat->hasil_pemeriksaan }}</td></tr>
        </table>
        <p>maka yang bersangkutan dinyatakan dalam keadaan <b>sehat</b>. Surat keterangan ini dipergunakan untuk: <b>{{ $surat->keperluan }}</b>.</p>
    @endif

    <p>Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>

    <div class="ttd">
        <div class="tgl">{{ $surat->tempat }}, {{ $tgl($surat->tanggal_surat) }}</div>
        <div class="peran">Dokter yang memeriksa,</div>
        <div class="gambar">
            @if ($surat->ttd_dokter)
                <img src="{{ $surat->ttd_dokter }}" alt="Tanda tangan dokter"@if ($ttdW) width="{{ $ttdW }}" height="{{ $ttdH }}"@endif>
            @endif
        </div>
        <div class="nm">{{ $surat->dokter }}</div>
    </div>
</div>
</body>
</html>
