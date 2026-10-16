{{-- Lembar Resume Medis. Butuh variabel $resume (hasil RekamMedisController::susunResume). --}}
@php
    $r = $resume;

    $svgLogo = '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="47" fill="#fff" stroke="#d0121b" stroke-width="6"/><circle cx="50" cy="50" r="33" fill="#d0121b"/><text x="50" y="47" font-family="Arial" font-size="10" font-weight="bold" fill="#fff" text-anchor="middle">MERAH</text><text x="50" y="60" font-family="Arial" font-size="10" font-weight="bold" fill="#fff" text-anchor="middle">PUTIH</text></svg>';
    $fileLogo = public_path('images/logo-klinik.png');
    $logo = file_exists($fileLogo)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($fileLogo))
        : 'data:image/svg+xml;base64,' . base64_encode($svgLogo);

    $minBaris = max(6, count($r['farmasi']));
@endphp

<div class="rsm">
    {{-- Kop --}}
    <table class="rsm-kop">
        <tr>
            <td class="logo"><img src="{{ $logo }}" alt="Logo Klinik"></td>
            <td class="teks">
                <h1>KLINIK UTAMA MERAH PUTIH</h1>
                <p>Jl. Ronggowarsito No. 98 A Ngawi</p>
                <p>Telp. (0351) 748186 / 081313142833 Email: <u>merahputihklinik@gmail.com</u></p>
                <p>Izin operasional: 12230024425350001</p>
            </td>
        </tr>
    </table>
    <hr class="rsm-garis">

    <div class="rsm-judul">RESUME MEDIS</div>

    {{-- Identitas (dari Pendaftaran) --}}
    <table class="rsm-id">
        <tr><td class="k">No. RM</td><td class="t">:</td><td>{{ $r['no_rm'] }}</td></tr>
        <tr><td class="k">Nama Peserta</td><td class="t">:</td><td>{{ $r['nama'] }}</td></tr>
        <tr><td class="k">Tgl. Lahir</td><td class="t">:</td><td>{{ $r['tgl_lahir'] }}</td></tr>
        <tr><td class="k">Poli Tujuan</td><td class="t">:</td><td>{{ $r['poli'] }}</td></tr>
        <tr><td class="k">DPJP</td><td class="t">:</td><td>{{ $r['dpjp'] }}</td></tr>
        <tr><td class="k">Tanggal Masuk</td><td class="t">:</td><td>{{ $r['tgl_masuk'] }}</td></tr>
    </table>

    <table class="rsm-tbl">
        <thead>
            <tr><th class="kiri">RESUME PEMERIKSAAN</th><th>KODE-ICD</th></tr>
        </thead>
        <tbody>
            {{-- Anamnesa, pemeriksaan fisik & penunjang --}}
            <tr class="baris">
                <td class="kiri">
                    <div class="rsm-blok">
                        <div>Anamnesa :</div>
                        <div class="rsm-isi">{{ $r['anamnesa'] !== '' ? $r['anamnesa'] : '-' }}</div>
                    </div>
                    <div class="rsm-blok">
                        <div>Pemeriksaan Fisik &amp; Penunjang serta Rangkuman</div>
                    </div>

                    <div class="rsm-blok">
                        <div class="rsm-sub">POLI UTAMA</div>
                        @if ($r['poli_utama'] !== '')
                            <div class="rsm-isi">{{ $r['poli_utama'] }}</div>
                        @else
                            <div class="rsm-hint">(Ringkasan hasil pemeriksaan pada poli yang di tuju)</div>
                        @endif
                    </div>

                    <div class="rsm-blok">
                        <div class="rsm-sub">POLI KONSULAN</div>
                        @forelse ($r['konsulan'] as $ks)
                            <div class="rsm-isi">{{ $ks['poli'] }}{{ $ks['ringkas'] !== '' ? ': ' . $ks['ringkas'] : '' }}</div>
                        @empty
                            <div class="rsm-hint">(Ringkasan hasil pemeriksaan jika terdapat konsulan yang lain)</div>
                        @endforelse
                    </div>

                    <table class="rsm-vital">
                        @foreach ($r['vital'] as [$label, $nilai])
                            <tr>
                                <td class="k">{{ $label }}</td>
                                <td class="t">:</td>
                                <td>{{ filled($nilai) ? $nilai : '' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td></td>
            </tr>

            {{-- Diagnosa --}}
            <tr class="baris">
                <td class="kiri rsm-dx">
                    <div>Diagnosa :</div>
                    @if ($r['diagnosa'] !== '' || count($r['diagnosa_sekunder']))
                        <ol>
                            @if ($r['diagnosa'] !== '')
                                <li>{{ $r['diagnosa'] }}</li>
                            @endif
                            @foreach ($r['diagnosa_sekunder'] as $ds)
                                <li>{{ $ds }}</li>
                            @endforeach
                        </ol>
                    @endif
                </td>
                <td>
                    <div class="rsm-kode-baris">ICD 10 :</div>
                    <div class="rsm-kode-baris">Diagnosa Primer : {{ $r['icd10_primer'] }}</div>
                    <div>Diagnosa Sekunder : {{ $r['icd10_sekunder'] }}</div>
                </td>
            </tr>

            {{-- Terapi / Tindakan --}}
            <tr class="baris">
                <td class="kiri">
                    <div>Terapi/Tindakan :</div>
                    @if (count($r['tindakan']))
                        <ol style="margin:2px 0 0 18px;padding:0;">
                            @foreach ($r['tindakan'] as $t)
                                <li>{{ $t }}</li>
                            @endforeach
                        </ol>
                    @else
                        <div class="rsm-hint">(Terapi/tindakan yang diberikan kepada pasien)</div>
                    @endif
                </td>
                <td>ICD 9 : {{ $r['icd9'] }}</td>
            </tr>

            {{-- Farmasi --}}
            <tr class="baris">
                <td class="kiri rsm-farmasi">
                    <div>Farmasi :</div>
                    <ol>
                        @for ($i = 0; $i < $minBaris; $i++)
                            <li>{{ $r['farmasi'][$i] ?? '' }}</li>
                        @endfor
                    </ol>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

    {{-- Tanda tangan DPJP --}}
    <div class="rsm-ttd">
        <div>Ngawi, {{ $r['tgl_ttd'] }}</div>
        <div>DPJP</div>
        @if ($r['ttd'])
            <div class="gambar"><img src="{{ $r['ttd'] }}" alt="Tanda tangan DPJP"></div>
        @else
            <div class="gap"></div>
        @endif
        <div class="nama">( {{ $r['dpjp'] ?: '....................................' }} )</div>
    </div>
</div>
