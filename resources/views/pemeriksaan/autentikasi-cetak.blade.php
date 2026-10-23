@php
    $lahir  = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $waktu  = $autentikasi->waktu_autentikasi->copy()->locale('id');
    $rm     = $kunjungan->rekamMedis;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Autentikasi Pemeriksaan — {{ $pasien->nama }}</title>
<style>
    *{box-sizing:border-box}
    body{font-family:'Times New Roman',serif;color:#000;margin:0;background:#eee}
    .page{width:210mm;min-height:297mm;margin:12px auto;background:#fff;padding:20mm 20mm 16mm}

    /* Kop surat: sama dengan Surat Rujukan, General Consent, dan Informed Consent */
    .kop{display:flex;align-items:center;gap:14px;border-bottom:4px double #000;padding-bottom:10px;margin-bottom:18px}
    .kop .logo{width:92px;flex-shrink:0}
    .kop .logo img,.kop .logo svg{display:block;width:100%;height:auto}
    .kop .teks{flex:1;text-align:center;padding-right:92px}
    .kop h1{margin:0 0 3px;font-size:22px;letter-spacing:.5px;color:#d0121b}
    .kop p{margin:1px 0 0;font-size:11px;color:#1a1a3a}

    h2{text-align:center;font-size:16px;text-decoration:underline;margin:0 0 4px}
    .sub{text-align:center;font-size:12px;margin-bottom:18px}

    .status{display:block;width:max-content;margin:0 auto 22px;padding:6px 26px;border:2px solid #1f8a4c;border-radius:8px;color:#1f8a4c;font-weight:bold;font-size:14px;letter-spacing:1px;-webkit-print-color-adjust:exact;print-color-adjust:exact}

    h3{font-size:13px;margin:0 0 6px;text-transform:uppercase}
    table.i{border-collapse:collapse;font-size:13px;margin-bottom:18px}
    table.i td{padding:3px 6px 3px 0;vertical-align:top}
    table.i td:first-child{width:170px}
    table.i td:nth-child(2){width:14px}

    p.ket{font-size:13px;line-height:1.55;margin:0 0 6px}

    .ttd{display:flex;justify-content:flex-end;margin-top:26px;font-size:13px}
    .ttd > div{width:72mm;text-align:center}
    .ttd .gambar{height:130px;margin:6px 0 4px;display:flex;align-items:center;justify-content:center}
    /* Tanda tangan dipangkas dari ruang kosong lalu diperbesar mengisi kotak (lihat skrip di bawah) */
    .ttd .gambar img{display:block;height:100%;width:auto;max-width:100%;object-fit:contain}
    .ttd .nm{font-weight:bold;text-decoration:underline}
    .ttd .jb{font-size:12px}

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

    <h2>AUTENTIKASI PEMERIKSAAN</h2>
    <div class="sub">Pengesahan seluruh data pemeriksaan oleh dokter/petugas</div>

    <span class="status">TERAUTENTIKASI</span>

    <h3>Data Pasien</h3>
    <table class="i">
        <tr><td>Nama Pasien</td><td>:</td><td>{{ $pasien->nama }}</td></tr>
        <tr><td>No. RM</td><td>:</td><td>{{ $pasien->no_rm }}</td></tr>
        <tr><td>NIK</td><td>:</td><td>{{ $pasien->nik ?: '-' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $pasien->jenis_kelamin ?: '-' }}</td></tr>
        <tr><td>Tanggal Lahir / Usia</td><td>:</td><td>{{ $lahir ? $lahir->format('d-m-Y') . ' / ' . $lahir->age . ' tahun' : '-' }}</td></tr>
        <tr><td>Poli</td><td>:</td><td>{{ $namaPoli }}</td></tr>
        <tr><td>Tanggal Kunjungan</td><td>:</td><td>{{ $kunjungan->tanggal_kunjungan->format('d-m-Y') }}</td></tr>
        @if ($rm && $rm->diagnosis_utama)
            <tr><td>Diagnosis Utama</td><td>:</td><td>{{ $rm->diagnosis_utama }}{{ $rm->kode_icd10 ? ' (' . $rm->kode_icd10 . ')' : '' }}</td></tr>
        @endif
    </table>

    <h3>Data Autentikasi</h3>
    <table class="i">
        <tr><td>Nama Dokter/Petugas</td><td>:</td><td>{{ $autentikasi->nama_dokter }}</td></tr>
        <tr><td>Jabatan</td><td>:</td><td>{{ $autentikasi->jabatan }}</td></tr>
        <tr><td>Tanggal &amp; Waktu</td><td>:</td><td>{{ $waktu->translatedFormat('j F Y, H:i') }} WIB</td></tr>
        <tr><td>Status</td><td>:</td><td>Terautentikasi</td></tr>
    </table>

    <p class="ket">Dengan ini dokter/petugas yang bertanda tangan di bawah menyatakan bahwa data asesmen, SOAP, dan diagnosis pada pemeriksaan pasien tersebut di atas telah diperiksa dan disahkan.</p>

    <div class="ttd">
        <div>
            Ngawi, {{ $waktu->translatedFormat('j F Y') }}<br>Dokter/Petugas pemeriksa,
            <div class="gambar">
                @if ($autentikasi->tanda_tangan)
                    <img id="ttdImg" src="{{ $autentikasi->tanda_tangan }}" alt="Tanda tangan">
                @endif
            </div>
            <div class="nm">{{ $autentikasi->nama_dokter }}</div>
            <div class="jb">{{ $autentikasi->jabatan }}</div>
        </div>
    </div>
</div>
<script>
// Pangkas ruang kosong di sekeliling tanda tangan supaya goresannya tampil besar dan rapi saat dicetak
(function () {
    var img = document.getElementById('ttdImg');
    if (!img) return;

    function pangkas() {
        try {
            var w = img.naturalWidth, h = img.naturalHeight;
            if (!w || !h) return;
            var c = document.createElement('canvas');
            c.width = w; c.height = h;
            var x = c.getContext('2d');
            x.drawImage(img, 0, 0);
            var d = x.getImageData(0, 0, w, h).data;
            var minX = w, minY = h, maxX = -1, maxY = -1;
            for (var yy = 0; yy < h; yy++) {
                for (var xx = 0; xx < w; xx++) {
                    var i = (yy * w + xx) * 4;
                    // piksel "tinta": tidak transparan dan bukan putih
                    if (d[i + 3] > 20 && (d[i] + d[i + 1] + d[i + 2]) < 720) {
                        if (xx < minX) minX = xx;
                        if (xx > maxX) maxX = xx;
                        if (yy < minY) minY = yy;
                        if (yy > maxY) maxY = yy;
                    }
                }
            }
            if (maxX < 0) return;                      // kanvas kosong, biarkan apa adanya
            var pad = 8;
            minX = Math.max(0, minX - pad); minY = Math.max(0, minY - pad);
            maxX = Math.min(w - 1, maxX + pad); maxY = Math.min(h - 1, maxY + pad);
            var cw = maxX - minX + 1, ch = maxY - minY + 1;
            var out = document.createElement('canvas');
            out.width = cw; out.height = ch;
            out.getContext('2d').drawImage(c, minX, minY, cw, ch, 0, 0, cw, ch);
            img.src = out.toDataURL('image/png');
        } catch (e) { /* gagal memangkas: tampilkan gambar asli */ }
    }

    if (img.complete) pangkas(); else img.addEventListener('load', pangkas, { once: true });
})();
</script>
<script>
// Dialog cetak langsung terbuka begitu lembar selesai dimuat (tombol "Cetak" di pojok tetap ada untuk cetak ulang)
window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 600); });
</script>
</body>
</html>
