@extends('layouts.pemeriksaan', ['tabAktif' => 'Informed Consent'])

@php
    // Identitas terisi otomatis dari data pendaftaran
    $dokter = $kunjungan->dokter ?? '';

    // Jenis informasi (isinya diketik sendiri oleh dokter)
    $daftarInformasi = [
        'Diagnosis'                            => 'Tuliskan diagnosis pasien. Contoh: Abses pada regio lengan kiri',
        'Dasar diagnosis'                      => 'Tuliskan dasar penegakan diagnosis (anamnesis, pemeriksaan fisik, penunjang)',
        'Tindakan kedokteran'                  => 'Tuliskan tindakan kedokteran yang akan dilakukan',
        'Indikasi tindakan'                    => 'Tuliskan alasan/indikasi tindakan dilakukan',
        'Tata cara'                            => 'Jelaskan tata cara/langkah tindakan secara singkat',
        'Tujuan tindakan'                      => 'Tuliskan tujuan dan manfaat tindakan',
        'Risiko bila tindakan dilakukan'       => 'Tuliskan risiko dan efek samping yang mungkin terjadi',
        'Risiko bila tindakan tidak dilakukan' => 'Tuliskan risiko bila tindakan tidak dilakukan',
        'Komplikasi tindakan'                  => 'Tuliskan komplikasi yang mungkin terjadi',
        'Prognosis'                            => 'Tuliskan perkiraan perjalanan/hasil penyakit setelah tindakan',
        'Alternatif dan risiko'                => 'Tuliskan alternatif tindakan lain beserta risikonya',
    ];

    $hubungan = ['Diri Sendiri', 'Suami', 'Istri', 'Anak', 'Orang Tua', 'Wali', 'Lainnya'];
@endphp

@push('styles')
<style>
    .icu-wrap{background:#fff;border:1px solid #666;border-radius:12px;padding:24px 28px 30px;margin-bottom:18px;font-family:'Poppins',sans-serif}
    .icu-judul-cetak{display:none}

    .icu-banner{padding:14px 18px;margin:0 0 22px;border-radius:6px;color:#fff;font-size:11px;font-weight:600;line-height:1.55;background:linear-gradient(90deg,#b01c1c 0%,#6d0d0d 60%,#3f0808 100%)}
    .icu-banner.merah{background:linear-gradient(90deg,#c40000 0%,#7a0e0e 100%);margin:18px 0 18px;padding:9px 14px;font-size:9.5px}

    .icu-sec{margin:0 0 16px;font-size:13px;font-weight:600;color:#111}
    .icu-sec.b{margin-top:28px}
    .icu-sub{margin:0 0 12px;font-size:12px;font-weight:600;color:#111}

    .icu-lbl{display:block;margin-bottom:6px;font-size:10px;font-weight:600;color:#222}
    .icu-in,.icu-ta,.icu-sel{width:100%;height:36px;padding:0 12px;border:1px solid #9a9a9a;border-radius:8px;background:#fff;font-family:'Poppins',sans-serif;font-size:10.5px;color:#222;outline:none}
    .icu-ta{height:auto;min-height:62px;padding:9px 12px;resize:none;overflow:hidden;line-height:1.5;display:block}
    .icu-ta::-webkit-scrollbar{display:none}
    .icu-in::placeholder,.icu-ta::placeholder{color:#9a9a9a;font-weight:400}
    .icu-sel:invalid,.icu-sel option[value='']{color:#9a9a9a}
    .icu-in:read-only{background:#f4f4f4;color:#444}
    .icu-in:focus,.icu-ta:focus,.icu-sel:focus{outline:2px solid #c00000;outline-offset:1px;border-color:#c00000;box-shadow:none}

    .icu-row3{display:grid;grid-template-columns:repeat(3,1fr);gap:22px 26px;margin-bottom:18px}
    .icu-row2{display:grid;grid-template-columns:1fr 1fr;gap:22px 26px;margin-bottom:18px}
    .icu-row5{display:grid;grid-template-columns:1fr 130px 1fr 1fr;gap:18px 22px;margin-bottom:18px}

    .icu-tandai{display:flex;justify-content:flex-end;margin:6px 0 16px}
    .icu-btn-hijau{height:36px;padding:0 22px;border:0;border-radius:6px;background:#4cd05f;color:#fff;font-family:'Poppins',sans-serif;font-size:10.5px;font-weight:600;cursor:pointer}
    .icu-btn-hijau:hover{background:#3bb94e}

    /* Tabel pemberian informasi */
    .icu-tbl{width:100%;border-collapse:separate;border-spacing:0;margin-bottom:6px}
    .icu-tbl th{padding:11px 14px;background:linear-gradient(90deg,#c40000 0%,#6d0d0d 100%);color:#fff;font-size:10.5px;font-weight:600;text-align:center}
    .icu-tbl th:first-child{border-radius:8px 0 0 8px}
    .icu-tbl th:last-child{border-radius:0 8px 8px 0}
    .icu-tbl td{padding:12px 14px;border-bottom:1px solid #ececec;font-size:10.5px;font-weight:600;color:#111;vertical-align:middle}
    .icu-tbl td.no{width:70px;text-align:center}
    .icu-tbl td.jenis{width:210px;text-align:center}
    .icu-tbl td.cek{width:150px;text-align:center}

    .icu-box{appearance:none;-webkit-appearance:none;width:18px;height:18px;margin:0;border:1px solid #888;border-radius:3px;background:#fff;cursor:pointer;vertical-align:middle;position:relative;flex:none}
    .icu-box:checked{background:#b40000;border-color:#b40000}
    .icu-box:checked::after{content:'';position:absolute;left:50%;top:44%;width:5px;height:10px;border:solid #fff;border-width:0 2px 2px 0;transform:translate(-50%,-50%) rotate(45deg)}
    .icu-box:focus-visible{outline:2px solid #c00000;outline-offset:2px}

    /* Tanda tangan */
    .icu-ttd{margin:0 0 20px}
    .icu-ttd>h4{margin:0 0 8px;font-size:10px;font-weight:600;color:#222}
    .icu-ttd-box{position:relative;border:1px solid #9a9a9a;border-radius:10px;background:#fff;overflow:hidden}
    .icu-ttd-box canvas{display:block;width:100%;height:120px;touch-action:none;cursor:crosshair}
    .icu-ttd-box .ph{position:absolute;left:0;right:0;top:46%;transform:translateY(-50%);text-align:center;font-size:10px;color:#888;pointer-events:none}
    .icu-ttd-box.terisi .ph{display:none}
    .icu-ttd-bar{display:flex;justify-content:space-between;align-items:center;padding:0 10px 7px;font-size:9px;color:#777}
    .icu-ttd-clear{border:0;background:none;padding:0;font-family:'Poppins',sans-serif;font-size:9.5px;font-weight:600;color:#c00000;cursor:pointer}

    /* Identitas pasien (otomatis) */
    .icu-ident{display:grid;grid-template-columns:150px 230px;gap:14px 0;align-items:center;margin-bottom:26px}
    .icu-ident .icu-lbl{margin:0;font-size:11px;font-weight:600}

    /* Keputusan */
    .icu-keputusan{display:grid;grid-template-columns:1fr 1fr;gap:34px;margin:6px 0 18px}
    .icu-pilih{height:38px;border:2px solid transparent;border-radius:8px;color:#fff;font-family:'Poppins',sans-serif;font-size:10.5px;font-weight:600;cursor:pointer;opacity:.55;transition:opacity .15s}
    .icu-pilih.setuju{background:#4cd05f}
    .icu-pilih.tolak{background:#d35656}
    .icu-pilih.aktif{opacity:1;border-color:#222;box-shadow:0 0 0 2px rgba(0,0,0,.08)}
    .icu-tolak-box{margin-bottom:18px}
    .icu-cek-tolak{display:flex;align-items:center;gap:10px;margin-top:12px;font-size:10.5px;font-weight:600;color:#222;cursor:pointer}

    .icu-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:10px}
    .icu-cetak{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 26px;border:1.5px solid #b40000;border-radius:6px;background:#fff;color:#b40000;font-family:'Poppins',sans-serif;font-size:11.5px;font-weight:600;cursor:pointer}
    .icu-cetak:hover{background:#fdecec}
    .icu-simpan{height:36px;padding:0 34px;border:0;border-radius:6px;background:#17c200;color:#fff;font-family:'Poppins',sans-serif;font-size:11.5px;font-weight:600;cursor:pointer}
    .icu-simpan:hover{background:#12a300}

    .icu-err{margin:0 0 14px;padding:10px 14px;border-radius:6px;background:#fdecea;color:#b3261e;font-size:11px;font-weight:500}
    .icu-ok{margin:0 0 14px;padding:10px 14px;border-radius:6px;background:#e8f7ea;color:#14602a;font-size:11px;font-weight:500}
    [hidden]{display:none!important}

    /* Kop surat: hanya tampil saat dicetak, sama dengan Formulir General Consent */
    .icu-kop{display:none}

    /* ===== CETAK ===== */
    @media print{
        .icu-kop{display:flex;align-items:center;gap:14px;border-bottom:4px double #000;padding-bottom:10px;margin-bottom:18px;font-family:'Times New Roman',serif;break-inside:avoid;page-break-inside:avoid}
        .icu-kop .logo{width:92px;flex-shrink:0}
        .icu-kop .logo img,.icu-kop .logo svg{display:block;width:100%;height:auto}
        .icu-kop .teks{flex:1;text-align:center;padding-right:92px}
        .icu-kop h1{margin:0 0 3px;font-size:22px;letter-spacing:.5px;color:#d0121b;font-weight:700}
        .icu-kop p{margin:1px 0 0;font-size:11px;color:#1a1a3a}
        /* margin 0 = tanggal/jam & URL bawaan browser tidak ikut tercetak; jarak tepi diganti padding */
        @page{size:A4;margin:0}
        html,body{background:#fff!important}
        body{padding:12mm 14mm!important}
        .sidebar,.topbar,.identity-bar,.tabs,.flash,.toast-alert,.icu-banner.info,.icu-tandai,.icu-foot,.icu-ttd-clear,.icu-ttd-bar,.icu-ttd-box .ph,#icuMsg{display:none!important}
        html body:not(.sidebar-collapsed) .main,html body.sidebar-collapsed .main,.main{margin:0!important;width:100%!important;max-width:100%!important;min-height:0!important;background:#fff!important}
        .content{padding:0!important}
        .icu-wrap{border:0!important;border-radius:0!important;padding:0!important;margin:0!important}
        .icu-judul-cetak{display:block;margin:0 0 12px;font-size:18px;font-weight:700;text-align:center;color:#000}
        .icu-sec,.icu-sub{color:#000}
        .icu-banner.merah{background:#fff!important;color:#000!important;border:1px solid #000;border-radius:4px;padding:8px 12px;font-size:10px}
        .icu-in,.icu-ta,.icu-sel{border:1px solid #000!important;border-radius:4px!important;background:#fff!important;color:#000!important;outline:none!important;font-size:10.5px;resize:none!important}
        .icu-ta{overflow:hidden!important;scrollbar-width:none;min-height:0!important}
        .icu-ta::-webkit-scrollbar{display:none!important;width:0!important}
        .icu-in::placeholder,.icu-ta::placeholder{color:transparent!important}
        .icu-in,.icu-sel{appearance:none;-webkit-appearance:none}
        input[type=time]::-webkit-calendar-picker-indicator,input[type=date]::-webkit-calendar-picker-indicator{display:none}
        .icu-tbl th{background:#fff!important;color:#000!important;border:1px solid #000;border-radius:0!important}
        .icu-tbl td{border-bottom:1px solid #555!important;padding-top:6px;padding-bottom:6px}
        .icu-tbl tr,.icu-ttd,.icu-ident,.icu-banner,.icu-keputusan{break-inside:avoid;page-break-inside:avoid}
        .icu-ttd-box{border-color:#000!important;border-radius:4px!important}
        .icu-ttd-box canvas{background:#fff!important}
        .icu-pilih{display:none!important}
        .icu-pilih.aktif{display:block!important;opacity:1;background:#fff!important;color:#000!important;border:1px solid #000!important;box-shadow:none}
        .icu-keputusan{grid-template-columns:1fr}
        .icu-box{border:1px solid #000!important}
        .icu-box:checked{background:#000!important}
        *{-webkit-print-color-adjust:exact;print-color-adjust:exact}
    }

    @media (max-width:900px){
        .icu-row3,.icu-row2,.icu-row5,.icu-keputusan{grid-template-columns:1fr}
        .icu-ident{grid-template-columns:1fr}
        .icu-tbl td.jenis{width:auto}
    }
</style>
@endpush

@section('konten')
<form class="icu-wrap" id="formIc" novalidate>
    @csrf
    {{-- Kop surat (hanya tampil saat cetak) --}}
    <div class="icu-kop">
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
    <h2 class="icu-judul-cetak">Informed Consent</h2>

    <div class="icu-banner info">
        Formulir persetujuan/penolakan tindakan kedokteran untuk {{ $namaPoli ?? 'Poli Umum' }}. Identitas pasien terisi otomatis dari data pendaftaran. Dokter mengetik sendiri nama tindakan dan isi informasinya; data pihak yang bertanda tangan diisi manual.
    </div>

    <div id="icuMsg" hidden></div>

    {{-- A. Pemberian Informasi --}}
    <h3 class="icu-sec">A. Pemberian Informasi</h3>

    <div class="icu-row3">
        <div>
            <label class="icu-lbl" for="icuDokter">Dokter Pelaksana Tindakan</label>
            @include('pemeriksaan._pilih-dokter', ['id' => 'icuDokter', 'name' => 'dokter_pelaksana', 'class' => 'icu-sel', 'poli' => $poli, 'nilai' => $dokter])
        </div>
        <div>
            <label class="icu-lbl" for="icuPemberi">Pemberi Informasi</label>
            @include('pemeriksaan._pilih-dokter', ['id' => 'icuPemberi', 'name' => 'pemberi_informasi', 'class' => 'icu-sel', 'poli' => $poli, 'nilai' => $dokter])
        </div>
        <div>
            <label class="icu-lbl" for="icuPenerima">Penerima Informasi/Pemberi Persetujuan</label>
            <input type="text" class="icu-in" id="icuPenerima" name="penerima_informasi" placeholder="Nama pasien/wali penerima informasi">
        </div>
    </div>

    <div class="icu-row2">
        <div>
            <label class="icu-lbl" for="icuTindakan">Nama Tindakan Kedokteran (diketik dokter)</label>
            <input type="text" class="icu-in" id="icuTindakan" name="nama_tindakan" placeholder="Contoh: Insisi abses, penjahitan luka">
        </div>
        <div>
            <label class="icu-lbl" for="icuArea">Lokasi/Area Tindakan (opsional)</label>
            <input type="text" class="icu-in" id="icuArea" name="area_tindakan" placeholder="Contoh: Lengan kiri bagian atas">
        </div>
    </div>

    <div class="icu-tandai">
        <button type="button" class="icu-btn-hijau" id="icuTandaiSemua">Tandai semua sudah dijelaskan</button>
    </div>

    <table class="icu-tbl">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Informasi</th>
                <th>Isi Informasi</th>
                <th>Sudah Dijelaskan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daftarInformasi as $jenis => $petunjuk)
                <tr>
                    <td class="no">{{ $loop->iteration }}</td>
                    <td class="jenis">{{ $jenis }}</td>
                    <td><textarea class="icu-ta icu-isi" name="isi_informasi[{{ $loop->iteration }}]" rows="2" placeholder="{{ $petunjuk }}" aria-label="Isi informasi: {{ $jenis }}"></textarea></td>
                    <td class="cek"><input type="checkbox" class="icu-box icu-info" name="dijelaskan[]" value="{{ $loop->iteration }}" aria-label="{{ $jenis }} sudah dijelaskan"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="icu-banner merah">
        Dokter/pemberi informasi menyatakan telah menerangkan hal-hal di atas secara benar dan jelas serta memberi kesempatan bertanya/berdiskusi.
    </div>

    <div class="icu-ttd">
        <h4>Tanda Tangan Dokter/Pemberi Informasi</h4>
        <div class="icu-ttd-box">
            <canvas data-ttd="ttd_dokter" tabindex="0"></canvas>
            <span class="ph">Tanda Tangan Disini</span>
            <div class="icu-ttd-bar"><span>Gunakan mouse/jari untuk mentandatangani</span><button type="button" class="icu-ttd-clear">Bersihkan</button></div>
            <input type="hidden" name="ttd_dokter">
        </div>
    </div>

    {{-- B. Persetujuan / Penolakan Tindakan --}}
    <h3 class="icu-sec b">B. Persetujuan / Penolakan Tindakan</h3>

    <div class="icu-ident">
        <label class="icu-lbl" for="icuRm">No.Rekam Medis</label>
        <input type="text" class="icu-in" id="icuRm" value="{{ $pasien->no_rm ?? $rm }}" readonly>

        <label class="icu-lbl" for="icuNama">Nama Pasien</label>
        <input type="text" class="icu-in" id="icuNama" value="{{ $pasien->nama ?? '' }}" readonly>

        <label class="icu-lbl" for="icuJk">Jenis Kelamin Pasien</label>
        <input type="text" class="icu-in" id="icuJk" value="{{ $pasien->jenis_kelamin ?? '' }}" readonly>
    </div>

    <h4 class="icu-sub">Data yang Bertanda Tangan (Pasien/Wali) — wajib diisi manual</h4>
    <div class="icu-row5">
        <div>
            <label class="icu-lbl" for="ttpNama">Nama</label>
            <input type="text" class="icu-in" id="ttpNama" name="ttp_nama" placeholder="Nama lengkap yang menandatangani">
        </div>
        <div>
            <label class="icu-lbl" for="ttpUmur">Umur (Tahun)</label>
            <input type="number" min="0" max="150" class="icu-in" id="ttpUmur" name="ttp_umur" placeholder="Contoh: 35">
        </div>
        <div>
            <label class="icu-lbl" for="ttpJk">Jenis Kelamin</label>
            <select class="icu-sel" id="ttpJk" name="ttp_jk">
                <option value="">Pilih jenis kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div>
            <label class="icu-lbl" for="ttpHubungan">Hubungan dengan Pasien</label>
            <select class="icu-sel" id="ttpHubungan" name="ttp_hubungan">
                <option value="">Pilih hubungan dengan pasien</option>
                @foreach ($hubungan as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div style="margin-bottom:22px">
        <label class="icu-lbl" for="ttpAlamat">Alamat</label>
        <input type="text" class="icu-in" id="ttpAlamat" name="ttp_alamat" placeholder="Alamat lengkap penandatangan">
    </div>

    <h4 class="icu-sub">Keputusan Pasien/Wali</h4>
    <div class="icu-keputusan">
        <button type="button" class="icu-pilih setuju" data-keputusan="setuju">Setuju dilakukan tindakan</button>
        <button type="button" class="icu-pilih tolak" data-keputusan="tolak">Menolak dilakukan tindakan</button>
    </div>
    <input type="hidden" name="keputusan" id="icuKeputusan">

    <div class="icu-tolak-box" id="icuTolakBox" hidden>
        <label class="icu-lbl" for="icuAlasan" style="font-size:11px">Alasan Penolakan (wajib diisi)</label>
        <textarea class="icu-ta" id="icuAlasan" name="alasan_penolakan" rows="3" placeholder="Tuliskan alasan pasien/wali menolak tindakan"></textarea>
        <label class="icu-cek-tolak">
            <input type="checkbox" class="icu-box" id="icuPahamTolak" name="paham_penolakan" value="1">
            Pasien/wali telah mendapat penjelasan mengenai risiko dan konsekuensi dari penolakan tindakan.
        </label>
    </div>

    <div class="icu-row3">
        <div>
            <label class="icu-lbl" for="icuTanggal">Tempat, Tanggal</label>
            <input type="date" class="icu-in" id="icuTanggal" name="tanggal" value="{{ now()->format('Y-m-d') }}">
        </div>
        <div>
            <label class="icu-lbl" for="icuPukul">Pukul</label>
            <input type="time" class="icu-in" id="icuPukul" name="pukul" value="{{ now()->format('H:i') }}">
        </div>
        <div>
            <label class="icu-lbl" for="icuSaksi">Nama Saksi (opsional)</label>
            <input type="text" class="icu-in" id="icuSaksi" name="nama_saksi" placeholder="Nama saksi (jika ada)">
        </div>
    </div>

    <div class="icu-ttd">
        <h4>Tanda Tangan Pasien/Wali</h4>
        <div class="icu-ttd-box">
            <canvas data-ttd="ttd_pasien" tabindex="0"></canvas>
            <span class="ph">Tanda Tangan Disini</span>
            <div class="icu-ttd-bar"><span>Gunakan mouse/jari untuk mentandatangani</span><button type="button" class="icu-ttd-clear">Bersihkan</button></div>
            <input type="hidden" name="ttd_pasien">
        </div>
    </div>

    <div class="icu-ttd">
        <h4>Tanda Tangan Saksi (Opsional)</h4>
        <div class="icu-ttd-box">
            <canvas data-ttd="ttd_saksi" tabindex="0"></canvas>
            <span class="ph">Tanda Tangan Disini</span>
            <div class="icu-ttd-bar"><span>Gunakan mouse/jari untuk mentandatangani</span><button type="button" class="icu-ttd-clear">Bersihkan</button></div>
            <input type="hidden" name="ttd_saksi">
        </div>
    </div>

    <div class="icu-foot">
        <button type="button" class="icu-cetak" id="icuCetak"><i class="bi bi-printer"></i> Cetak</button>
        <button type="submit" class="icu-simpan">Simpan</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('formIc');
    var msg  = document.getElementById('icuMsg');

    // ---------- Textarea otomatis menyesuaikan tinggi (supaya isi tercetak utuh) ----------
    function sesuaikan(ta) {
        ta.style.height = 'auto';
        ta.style.height = Math.max(ta.scrollHeight + 2, 62) + 'px';
    }
    function sesuaikanSemua() { form.querySelectorAll('textarea').forEach(sesuaikan); }
    form.querySelectorAll('textarea').forEach(function (ta) {
        ta.addEventListener('input', function () { sesuaikan(ta); });
    });
    sesuaikanSemua();
    window.addEventListener('beforeprint', sesuaikanSemua);   // juga berlaku untuk Ctrl+P

    // ---------- Tandai semua sudah dijelaskan ----------
    var tandai = document.getElementById('icuTandaiSemua');
    tandai.addEventListener('click', function () {
        var semua = form.querySelectorAll('.icu-info');
        var sudahSemua = Array.prototype.every.call(semua, function (c) { return c.checked; });
        semua.forEach(function (c) { c.checked = ! sudahSemua; });
        tandai.textContent = sudahSemua ? 'Tandai semua sudah dijelaskan' : 'Batalkan semua tanda';
    });

    // ---------- Keputusan pasien/wali ----------
    var tombolPilih = form.querySelectorAll('.icu-pilih');
    var inputKeputusan = document.getElementById('icuKeputusan');
    var kotakTolak = document.getElementById('icuTolakBox');
    tombolPilih.forEach(function (b) {
        b.addEventListener('click', function () {
            inputKeputusan.value = b.getAttribute('data-keputusan');
            tombolPilih.forEach(function (x) { x.classList.toggle('aktif', x === b); });
            kotakTolak.hidden = inputKeputusan.value !== 'tolak';
            if (kotakTolak.hidden) {
                document.getElementById('icuAlasan').value = '';
                document.getElementById('icuPahamTolak').checked = false;
            } else {
                sesuaikan(document.getElementById('icuAlasan'));
            }
        });
    });

    // ---------- Kotak tanda tangan (canvas) ----------
    function siapkanTtd(canvas) {
        var ctx = canvas.getContext('2d');
        var box = canvas.parentNode;
        var hidden = box.querySelector('input[type="hidden"]');
        var clearBtn = box.querySelector('.icu-ttd-clear');
        var menggambar = false, adaGoresan = false;

        function ukuran() {
            var r = canvas.getBoundingClientRect(), dpr = window.devicePixelRatio || 1;
            canvas.width = r.width * dpr;
            canvas.height = r.height * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#111';
            adaGoresan = false; hidden.value = '';
            box.classList.remove('terisi');
        }
        function pos(e) {
            var r = canvas.getBoundingClientRect();
            return { x: e.clientX - r.left, y: e.clientY - r.top };
        }
        canvas.addEventListener('pointerdown', function (e) {
            menggambar = true; canvas.setPointerCapture(e.pointerId);
            var p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y);
        });
        canvas.addEventListener('pointermove', function (e) {
            if (! menggambar) return;
            var p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke();
            adaGoresan = true; box.classList.add('terisi');
        });
        function selesai() {
            if (! menggambar) return;
            menggambar = false;
            if (adaGoresan) hidden.value = canvas.toDataURL('image/png');
        }
        canvas.addEventListener('pointerup', selesai);
        canvas.addEventListener('pointerleave', selesai);
        clearBtn.addEventListener('click', ukuran);
        ukuran();
    }
    document.querySelectorAll('canvas[data-ttd]').forEach(siapkanTtd);

    // ---------- Cetak ----------
    document.getElementById('icuCetak').addEventListener('click', function () {
        sesuaikanSemua();   // isi panjang ikut tercetak utuh
        window.print();
    });

    // ---------- Simpan (validasi tampilan) ----------
    function tampil(jenis, teks) {
        msg.hidden = false;
        msg.className = jenis === 'ok' ? 'icu-ok' : 'icu-err';
        msg.textContent = teks;
        msg.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var kurang = [];
        if (! form.dokter_pelaksana.value.trim()) kurang.push('Dokter Pelaksana Tindakan');
        if (! form.pemberi_informasi.value.trim()) kurang.push('Pemberi Informasi');
        if (! form.penerima_informasi.value.trim()) kurang.push('Penerima Informasi');
        if (! form.nama_tindakan.value.trim()) kurang.push('Nama Tindakan Kedokteran');

        var adaInfo = Array.prototype.some.call(form.querySelectorAll('.icu-info:checked'), function (c) {
            return form.elements['isi_informasi[' + c.value + ']'].value.trim() !== '';
        });
        if (! adaInfo) kurang.push('minimal satu Isi Informasi yang diisi dan ditandai sudah dijelaskan');

        if (! form.ttd_dokter.value) kurang.push('Tanda tangan dokter');
        if (! form.ttp_nama.value.trim()) kurang.push('Nama penandatangan');
        if (! form.ttp_umur.value) kurang.push('Umur');
        if (! form.ttp_jk.value) kurang.push('Jenis kelamin penandatangan');
        if (! form.ttp_hubungan.value) kurang.push('Hubungan dengan pasien');
        if (! form.ttp_alamat.value.trim()) kurang.push('Alamat');
        if (! inputKeputusan.value) kurang.push('Keputusan pasien/wali');
        if (inputKeputusan.value === 'tolak') {
            if (! form.alasan_penolakan.value.trim()) kurang.push('Alasan penolakan');
            if (! form.paham_penolakan.checked) kurang.push('Centang penjelasan risiko penolakan');
        }
        if (! form.tanggal.value) kurang.push('Tempat, Tanggal');
        if (! form.pukul.value) kurang.push('Pukul');
        if (! form.ttd_pasien.value) kurang.push('Tanda tangan pasien/wali');

        if (kurang.length) {
            tampil('err', 'Lengkapi dulu: ' + kurang.join(', ') + '.');
            return;
        }
        // TODO: kirim ke server setelah route & tabel penyimpanan Informed Consent dibuat
        tampil('ok', 'Formulir Informed Consent lengkap.');
    });
})();
</script>
@endpush
