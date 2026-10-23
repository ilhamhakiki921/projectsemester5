@php
    $pemberiWali = $consent && $consent->pemberi_persetujuan === 'Wali';
    $setujuIdx   = $consent ? $consent->pernyataanDisetujui() : [];
    $lahir       = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $tglConsent  = $consent && $consent->disetujui_pada ? $consent->disetujui_pada : null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Formulir General Consent — {{ $pasien->nama }}</title>
<style>
    *{box-sizing:border-box}
    body{font-family:'Times New Roman',serif;color:#000;margin:0;background:#eee}
    .page{width:210mm;min-height:297mm;margin:12px auto;background:#fff;padding:20mm 20mm 16mm}

    /* Kop surat: sama dengan Surat Rujukan di menu Pemeriksaan */
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

    table.pernyataan{width:100%;border-collapse:collapse;margin-bottom:14px;font-size:12.5px}
    table.pernyataan th,table.pernyataan td{border:1px solid #000;padding:5px 8px;vertical-align:top;line-height:1.45}
    table.pernyataan th{background:#eee;text-align:left}
    .c-no{width:34px;text-align:center}
    .c-sifat{width:66px}
    .c-putusan{width:76px;text-align:center}
    .judul{font-weight:bold}

    /* Blok tanda tangan: tiga kolom sama lebar, garis tanda tangan sejajar */
    .ttd{display:grid;grid-template-columns:repeat(3,1fr);column-gap:24px;margin-top:30px;font-size:13px;page-break-inside:avoid;break-inside:avoid}
    .ttd > div{text-align:center;display:flex;flex-direction:column}
    .ttd .tgl{min-height:18px;line-height:18px}
    .ttd .jabatan{min-height:18px;line-height:18px;font-weight:bold}
    .ttd .garis{height:76px;margin:6px 8px 0;border-bottom:1px solid #000;display:flex;align-items:center;justify-content:center}
    .ttd .garis img{max-height:70px;max-width:100%}
    .ttd .nm{font-weight:bold;min-height:18px;margin-top:6px;word-break:break-word}
    .ttd .peran{font-size:12px;margin-top:2px;min-height:16px}
    .catatan{border:1px solid #000;padding:6px 10px;font-size:11.5px;line-height:1.45;margin-top:14px}

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

    <h2>FORMULIR GENERAL CONSENT</h2>
    <div class="no">Persetujuan Umum Tindakan Medis Pasien</div>

    <table class="i">
        <tr><td>Nama Pasien</td><td>:</td><td>{{ $pasien->nama }}</td></tr>
        <tr><td>No. RM</td><td>:</td><td>{{ $pasien->no_rm }}</td></tr>
        <tr><td>NIK</td><td>:</td><td>{{ $pasien->nik ?: '-' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $pasien->jenis_kelamin ?: '-' }}</td></tr>
        <tr><td>Tanggal Lahir / Usia</td><td>:</td><td>{{ $lahir ? $lahir->format('d-m-Y') . ' / ' . $lahir->age . ' tahun' : '-' }}</td></tr>
        <tr><td>Alamat</td><td>:</td><td>{{ $pasien->alamat ?: '-' }}</td></tr>
        <tr><td>Penandatangan</td><td>:</td><td>{{ $consent->nama_penyetuju ?? '' }}</td></tr>
        <tr><td>Hubungan dengan pasien</td><td>:</td><td>{{ $consent ? ($pemberiWali ? $consent->hubungan : 'Pasien sendiri') : '' }}</td></tr>
        <tr><td>Keluarga/pendamping</td><td>:</td><td>{{ $consent->pendamping ?? '' }}</td></tr>
        @php $df = $consent->data_form ?? []; @endphp
        @if (! empty($df))
            <tr><td>Riwayat alergi</td><td>:</td><td>{{ $df['riwayat_alergi'] ?? '' ?: '-' }}</td></tr>
            <tr><td>Riwayat penyakit dahulu</td><td>:</td><td>{{ $df['riwayat_penyakit'] ?? '' ?: '-' }}</td></tr>
            <tr><td>Kerabat penerima informasi</td><td>:</td><td>
                @forelse (($df['kerabat'] ?? []) as $k)
                    {{ $k['nama'] }}@if (! empty($k['hubungan'])) ({{ $k['hubungan'] }})@endif{{ ! $loop->last ? ', ' : '' }}
                @empty
                    -
                @endforelse
            </td></tr>
            <tr><td>Izin kunjungan</td><td>:</td><td>{{ $df['izin_kunjungan'] ?? '-' }}</td></tr>
            <tr><td>Privasi khusus</td><td>:</td><td>{{ $df['privasi_khusus'] ?? '-' }}@if (! empty($df['keterangan_privasi'])) - {{ $df['keterangan_privasi'] }}@endif</td></tr>
        @endif
    </table>

    <p>Dengan ini saya menyatakan telah menerima penjelasan dan menyetujui hal-hal berikut:</p>

    <table class="pernyataan">
        <thead>
            <tr>
                <th class="c-no">No</th>
                <th>Pernyataan</th>
                <th class="c-sifat">Sifat</th>
                <th class="c-putusan">Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach (\App\Models\GeneralConsent::PERNYATAAN as $n => $row)
                <tr>
                    <td class="c-no">{{ $n + 1 }}</td>
                    <td>{{ $row[0] }}</td>
                    <td>{{ $row[1] }}</td>
                    <td class="c-putusan">{{ $consent ? (in_array($n, $setujuIdx, true) ? 'Setuju' : ($row[1] === 'Opsional' ? 'Tidak' : 'Belum')) : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:16px">Demikian persetujuan umum ini dibuat dengan sebenar-benarnya dan berlaku untuk seluruh kunjungan pasien.</p>

    <div class="catatan">
        <strong>Catatan:</strong> General Consent hanya diberikan satu kali kepada <strong>pasien baru</strong>.
        Pasien lama tidak diberikan General Consent pada kunjungan berikutnya.
    </div>

    @php
        $namaTtd = fn ($v) => '( ' . (filled($v) ? $v : '..........................') . ' )';
    @endphp
    <div class="ttd">
        <div>
            <div class="tgl">&nbsp;</div>
            <div class="jabatan">Penandatangan</div>
            <div class="garis">@if ($consent && $consent->ttd_penanggung_jawab)<img src="{{ $consent->ttd_penanggung_jawab }}" alt="Tanda tangan">@endif</div>
            <div class="nm">{{ $namaTtd($consent->nama_penyetuju ?? null) }}</div>
            <div class="peran">{{ $consent ? ($pemberiWali ? 'Wali pasien' . ($consent->hubungan ? ' (' . $consent->hubungan . ')' : '') : 'Pasien') : '' }}</div>
        </div>
        <div>
            <div class="tgl">&nbsp;</div>
            <div class="jabatan">Saksi</div>
            <div class="garis">@if ($consent && $consent->ttd_saksi)<img src="{{ $consent->ttd_saksi }}" alt="Tanda tangan">@endif</div>
            <div class="nm">{{ $namaTtd($consent->saksi ?? null) }}</div>
            <div class="peran">Saksi pasien</div>
        </div>
        <div>
            <div class="tgl">Ngawi, {{ ($tglConsent ?? now())->locale('id')->translatedFormat('j F Y') }}</div>
            <div class="jabatan">Petugas yang mencatat</div>
            <div class="garis">@if ($consent && $consent->ttd_petugas)<img src="{{ $consent->ttd_petugas }}" alt="Tanda tangan">@endif</div>
            <div class="nm">{{ $namaTtd($consent->petugas_pencatat ?? null) }}</div>
            <div class="peran">Petugas klinik</div>
        </div>
    </div>
</div>
<script>
// Dialog cetak langsung terbuka begitu lembar selesai dimuat (tombol "Cetak" di pojok tetap ada untuk cetak ulang)
window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 600); });
</script>
</body>
</html>
