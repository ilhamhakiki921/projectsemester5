@php
    $nilai   = fn ($nama) => trim((string) ($d[$nama][0] ?? ''));
    $umum    = $kunjungan->poli === 'umum';
    $lahir   = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $keputusan = $nilai('keputusan');

    $infoUmum = [
        'Diagnosis', 'Dasar diagnosis', 'Tindakan kedokteran', 'Indikasi tindakan', 'Tata cara',
        'Tujuan tindakan', 'Risiko bila tindakan dilakukan', 'Risiko bila tindakan tidak dilakukan',
        'Komplikasi tindakan', 'Prognosis', 'Alternatif dan risiko',
    ];
    $infoGigi = [
        'Diagnosis/kelainan gigi dan mulut berdasarkan hasil pemeriksaan.',
        'Hasil anamnesis, pemeriksaan gigi dan mulut serta pemeriksaan penunjang apabila diperlukan.',
        'Tindakan kedokteran gigi yang akan dilakukan.',
        'Nomor gigi/area yang akan dilakukan tindakan.',
        'Alasan medis dilakukannya tindakan berdasarkan kondisi dan diagnosis pasien.',
        'Tata cara tindakan sesuai prosedur kedokteran gigi, termasuk anestesi lokal apabila diperlukan.',
        'Tujuan & manfaat: mengatasi keluhan, menghilangkan sumber infeksi/kerusakan, menjaga fungsi gigi.',
        'Risiko & efek samping: nyeri, tidak nyaman, perdarahan, pembengkakan, reaksi obat/anestesi.',
        'Komplikasi: infeksi, perdarahan berkepanjangan, reaksi alergi, gangguan penyembuhan.',
        'Alternatif tindakan/perawatan lain yang sesuai kondisi pasien, termasuk observasi atau rujukan.',
        'Risiko bila tindakan tidak dilakukan: keluhan menetap/memburuk, nyeri, infeksi, gangguan fungsi.',
        'Prognosis dipengaruhi kondisi gigi/jaringan pendukung, kesehatan pasien, dan kepatuhan perawatan.',
    ];
    $tindakanGigi = [
        'pencabutan' => 'Pencabutan gigi', 'penambalan' => 'Penambalan/restorasi', 'scaling' => 'Scaling',
        'bedah_minor' => 'Tindakan bedah minor', 'saluran_akar' => 'Perawatan saluran akar', 'lainnya' => 'Lainnya',
    ];

    $dicentang = array_map('strval', $d[$umum ? 'dijelaskan[]' : 'informasi[]'] ?? []);

    $tglIc = $umum
        ? ($nilai('tanggal') ? \Carbon\Carbon::parse($nilai('tanggal'))->locale('id')->translatedFormat('j F Y') : '')
        : $nilai('tempat_tanggal');
    $tempatTgl = $umum ? 'Ngawi, ' . $tglIc : $tglIc;

    $jkTtp = ['L' => 'Laki-laki', 'P' => 'Perempuan'][$nilai('ttp_jk')] ?? $nilai('ttp_jk');

    $pilihTindakan = collect($d['tindakan[]'] ?? [])
        ->map(fn ($kode) => $kode === 'lainnya' && $nilai('tindakan_lainnya')
            ? 'Lainnya: ' . $nilai('tindakan_lainnya')
            : ($tindakanGigi[$kode] ?? $kode))
        ->implode(', ');

    $ttdBaris = fn ($n) => str_starts_with($nilai($n), 'data:image/') ? $nilai($n) : null;
    $namaTtd  = fn ($v) => '( ' . (filled($v) ? $v : '..........................') . ' )';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Informed Consent — {{ $pasien->nama }}</title>
<style>
    *{box-sizing:border-box}
    body{font-family:'Times New Roman',serif;color:#000;margin:0;background:#eee}
    .page{width:210mm;min-height:297mm;margin:12px auto;background:#fff;padding:20mm 20mm 16mm}

    /* Kop surat: sama dengan General Consent, Surat Rujukan & Autentikasi */
    .kop{display:flex;align-items:center;gap:14px;border-bottom:4px double #000;padding-bottom:10px;margin-bottom:18px}
    .kop .logo{width:92px;flex-shrink:0}
    .kop .logo img,.kop .logo svg{display:block;width:100%;height:auto}
    .kop .teks{flex:1;text-align:center;padding-right:92px}
    .kop h1{margin:0 0 3px;font-size:22px;letter-spacing:.5px;color:#d0121b}
    .kop p{margin:1px 0 0;font-size:11px;color:#1a1a3a}

    h2{text-align:center;font-size:16px;text-decoration:underline;margin:0 0 4px}
    .sub{text-align:center;font-size:12px;margin-bottom:18px}
    h3{font-size:13px;margin:16px 0 6px;text-transform:uppercase}
    table.i{border-collapse:collapse;font-size:13px;margin-bottom:10px}
    table.i td{padding:2px 6px 2px 0;vertical-align:top}
    table.i td:first-child{width:200px}
    table.i td:nth-child(2){width:14px}
    p{font-size:13px;line-height:1.5;margin:0 0 10px}

    table.info{width:100%;border-collapse:collapse;margin-bottom:12px;font-size:12.5px}
    table.info th,table.info td{border:1px solid #000;padding:5px 8px;vertical-align:top;line-height:1.45}
    table.info th{background:#eee;text-align:left;-webkit-print-color-adjust:exact;print-color-adjust:exact}
    table.info td.c,table.info th.c{text-align:center;width:90px}
    table.info td.no{width:34px;text-align:center}
    table.info tr{break-inside:avoid;page-break-inside:avoid}
    .isi{white-space:pre-wrap}

    .catatan{border:1px solid #000;padding:6px 10px;font-size:11.5px;line-height:1.45;margin:12px 0}

    .ttd{display:flex;justify-content:space-between;margin-top:26px;font-size:13px;break-inside:avoid;page-break-inside:avoid}
    .ttd > div{width:31%;text-align:center}
    .ttd .garis{height:72px;display:flex;align-items:center;justify-content:center;border-bottom:1px solid #000;margin:0 6px}
    .ttd .garis img{max-height:68px;max-width:100%}
    .ttd .nm{font-weight:bold;min-height:18px;margin-top:4px;word-break:break-word}
    .ttd .ket{font-size:10px;color:#444}
    .ttd .peran{font-size:11px;margin-top:2px}

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

    <h2>INFORMED CONSENT</h2>
    <div class="sub">Persetujuan / Penolakan Tindakan Kedokteran{{ $umum ? '' : ' Gigi' }} — {{ $namaPoli }}</div>

    <h3>Data Pasien</h3>
    <table class="i">
        <tr><td>Nama Pasien</td><td>:</td><td>{{ $pasien->nama }}</td></tr>
        <tr><td>No. RM</td><td>:</td><td>{{ $pasien->no_rm }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $pasien->jenis_kelamin ?: '-' }}</td></tr>
        <tr><td>Tanggal Lahir / Usia</td><td>:</td><td>{{ $lahir ? $lahir->format('d-m-Y') . ' / ' . $lahir->age . ' tahun' : '-' }}</td></tr>
        <tr><td>Tanggal Kunjungan</td><td>:</td><td>{{ $kunjungan->tanggal_kunjungan ? $kunjungan->tanggal_kunjungan->format('d-m-Y') : '-' }}</td></tr>
    </table>

    <h3>A. Pemberian Informasi</h3>
    <table class="i">
        <tr><td>Dokter Pelaksana</td><td>:</td><td>{{ $nilai('dokter_pelaksana') ?: '-' }}</td></tr>
        <tr><td>Pemberi Informasi</td><td>:</td><td>{{ $nilai('pemberi_informasi') ?: '-' }}</td></tr>
        <tr><td>Penerima Informasi</td><td>:</td><td>{{ $nilai('penerima_informasi') ?: '-' }}</td></tr>
        @if ($umum)
            <tr><td>Nama Tindakan</td><td>:</td><td>{{ $nilai('nama_tindakan') ?: '-' }}</td></tr>
        @else
            <tr><td>Jenis Tindakan</td><td>:</td><td>{{ $pilihTindakan ?: '-' }}</td></tr>
        @endif
        <tr><td>{{ $umum ? 'Lokasi/Area Tindakan' : 'Nomor Gigi / Area Tindakan' }}</td><td>:</td><td>{{ $nilai('area_tindakan') ?: '-' }}</td></tr>
    </table>

    <table class="info">
        <thead>
            <tr>
                <th class="c" style="width:34px">No</th>
                <th>Jenis Informasi</th>
                @if ($umum)<th>Isi Informasi</th>@endif
                <th class="c">{{ $umum ? 'Sudah Dijelaskan' : 'Sudah Disampaikan' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach (($umum ? $infoUmum : $infoGigi) as $i => $teks)
                <tr>
                    <td class="no">{{ $i + 1 }}</td>
                    <td>{{ $teks }}</td>
                    @if ($umum)
                        <td class="isi">{{ $nilai('isi_informasi[' . ($i + 1) . ']') ?: '-' }}</td>
                    @endif
                    <td class="c">{{ in_array((string) ($i + 1), $dicentang, true) ? '✔' : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="catatan">
        {{ $umum
            ? 'Dokter/pemberi informasi menyatakan telah menerangkan hal-hal di atas secara benar dan jelas serta memberi kesempatan bertanya/berdiskusi.'
            : 'Dokter gigi/pemberi informasi menyatakan telah memberikan informasi secara benar, jelas dan lengkap, serta memberi kesempatan bertanya. Pasien/penerima informasi menyatakan telah menerima dan memahami informasi tersebut.' }}
    </div>

    <h3>B. Persetujuan / Penolakan Tindakan</h3>
    <p>Saya yang bertanda tangan di bawah ini:</p>
    <table class="i">
        <tr><td>Nama</td><td>:</td><td>{{ $nilai('ttp_nama') ?: '-' }}</td></tr>
        <tr><td>Umur</td><td>:</td><td>{{ $nilai('ttp_umur') !== '' ? $nilai('ttp_umur') . ' tahun' : '-' }}</td></tr>
        <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $jkTtp ?: '-' }}</td></tr>
        <tr><td>Alamat</td><td>:</td><td>{{ $nilai('ttp_alamat') ?: '-' }}</td></tr>
        <tr><td>Hubungan dengan Pasien</td><td>:</td><td>{{ $nilai('ttp_hubungan') ?: '-' }}</td></tr>
    </table>

    <p>
        Selaku pasien/wali dari pasien <strong>{{ $pasien->nama }}</strong> (No. RM {{ $pasien->no_rm }}), setelah menerima penjelasan di atas, dengan ini
        <strong>{{ $keputusan === 'tolak' ? 'MENOLAK' : ($keputusan === 'setuju' ? 'MENYETUJUI' : '.....................') }}</strong>
        dilakukannya tindakan kedokteran{{ $umum ? '' : ' gigi' }} tersebut.
    </p>
    @if ($keputusan === 'tolak' && $umum)
        <table class="i">
            <tr><td>Alasan Penolakan</td><td>:</td><td class="isi">{{ $nilai('alasan_penolakan') ?: '-' }}</td></tr>
            <tr><td>Memahami risiko penolakan</td><td>:</td><td>{{ $nilai('paham_penolakan') === '1' ? 'Ya' : 'Belum' }}</td></tr>
        </table>
    @endif

    <p>Demikian pernyataan ini dibuat dengan sebenar-benarnya tanpa paksaan dari pihak mana pun.</p>

    <div class="ttd">
        <div>
            Dokter/Pemberi Informasi
            <div class="garis">@if ($ttdBaris('ttd_dokter'))<img src="{{ $ttdBaris('ttd_dokter') }}" alt="Tanda tangan">@endif</div>
            <div class="nm">{{ $namaTtd($nilai('pemberi_informasi')) }}</div>
            <div class="ket">Nama terang</div>
        </div>
        <div>
            {{ $tempatTgl ?: 'Ngawi' }}@if ($nilai('pukul')), pukul {{ $nilai('pukul') }}@endif<br>Pasien/Wali
            <div class="garis">@if ($ttdBaris('ttd_pasien'))<img src="{{ $ttdBaris('ttd_pasien') }}" alt="Tanda tangan">@endif</div>
            <div class="nm">{{ $namaTtd($nilai('ttp_nama')) }}</div>
            <div class="ket">Nama terang</div>
            @if ($nilai('ttp_hubungan'))<div class="peran">{{ $nilai('ttp_hubungan') }}</div>@endif
        </div>
        <div>
            Saksi
            <div class="garis">@if ($ttdBaris('ttd_saksi'))<img src="{{ $ttdBaris('ttd_saksi') }}" alt="Tanda tangan">@endif</div>
            <div class="nm">{{ $namaTtd($nilai('nama_saksi')) }}</div>
            <div class="ket">Nama terang</div>
        </div>
    </div>
</div>
<script>
// Dialog cetak langsung terbuka begitu lembar selesai dimuat (tombol "Cetak" di pojok tetap ada untuk cetak ulang)
window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 600); });
</script>
</body>
</html>
