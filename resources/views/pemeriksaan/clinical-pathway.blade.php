@extends('layouts.pemeriksaan', ['tabAktif' => 'Clinical Pathway'])

@push('styles')
<link rel="stylesheet" href="{{ asset('css/icd10.css') }}">
<style>
    .cp-card{border:2px solid #b40000;border-radius:10px;padding:20px 22px 24px;margin-bottom:18px;background:#fff}
    .cp-h{margin:0 0 4px;font-size:15px;font-weight:700;color:#ed1111}
    .cp-sub{margin:0 0 16px;font-size:13px;color:#222}
    .cp-grid3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px 22px;margin-bottom:16px}
    .cp-grid2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 44px}
    .cp-f{display:flex;flex-direction:column;gap:6px;min-width:0}
    .cp-f label{font-size:11px;font-weight:600;color:#111}
    .cp-f label i{color:#c00000;font-style:normal}
    .cp-f input,.cp-f select,.cp-f textarea{width:100%;height:38px;border:1px solid #333;border-radius:5px;padding:0 12px;font:inherit;font-size:12px;background:#fff;color:#111}
    .cp-f textarea{height:84px;padding:10px 12px;resize:vertical}
    .cp-f input::placeholder,.cp-f textarea::placeholder{color:#8a8a8a;font-size:11px}
    .cp-f select{cursor:pointer}
    .cp-f input:focus,.cp-f select:focus,.cp-f textarea:focus{outline:2px solid #c00000;outline-offset:1px}
    .cp-f.err input,.cp-f.err select{border-color:#c00000}

    .cp-chips{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 20px}
    .cp-chip{min-width:62px;padding:4px 12px 3px;border:1px solid #c00000;border-radius:5px;background:#fdecec;font:inherit;text-align:center;cursor:pointer;line-height:1.2}
    .cp-chip b{display:block;font-size:11px;font-weight:600}
    .cp-chip small{display:block;font-size:8px;color:#c00000;font-weight:700}
    .cp-chip[hidden],.cp-add[hidden]{display:none}
    .cp-add{width:38px;border:1px solid #c00000;border-radius:5px;background:#fff;color:#c00000;font:inherit;font-size:20px;font-weight:700;line-height:1;cursor:pointer}
    .cp-add:hover{background:#fdecec}
    .cp-chip.on{border-color:#c00000;background:#fdecec;box-shadow:0 0 0 1px #c00000}
    .cp-chip.terisi{border-color:#16a34a;background:#e8f7ee}
    .cp-chip.terisi small{color:#14803a;font-weight:700}
    .cp-chip.terisi.on{border-color:#16a34a;background:#d3f0de;box-shadow:0 0 0 1px #16a34a}

    .cp-panel[hidden]{display:none}
    .cp-kj-head{display:flex;align-items:baseline;gap:10px}
    .cp-kj-head strong{font-size:14px}
    .cp-kj-head span{font-size:9px;color:#555}
    .cp-kj-info{font-size:9px;color:#333;margin:2px 0 6px}
    .cp-hr{border:0;border-top:1px solid #999;margin:0 0 14px}

    .cp-box{border:1px solid #333;border-radius:5px;margin:22px 0 0;background:#fff;overflow:hidden}
    .cp-box-head{padding:8px 14px;font-size:11px;font-weight:600;border-bottom:1px solid #333}
    .cp-box-head.red{background:linear-gradient(90deg,#a81c1c,#600a0a);color:#fff;border-bottom:0}
    .cp-box-body{padding:12px 14px 22px}
    .cp-box-body > p{margin:0 0 8px;font-size:9.5px;color:#333}
    .cp-box-body.cost{padding:0}
    .cp-box-body.cost textarea{width:100%;height:84px;border:0;padding:12px 14px;font:inherit;font-size:12px;resize:vertical;display:block}
    .cp-box-body.cost textarea:focus{outline:2px solid #c00000;outline-offset:-2px}
    .cp-f-small{width:150px}
    .cp-check{display:inline-flex;align-items:center;gap:10px;cursor:pointer;font-size:12px;font-weight:600;color:#111}
    .cp-check-group{display:flex;flex-wrap:wrap;gap:12px 40px}
    .cp-check input{width:20px;height:20px;margin:0;accent-color:#16a34a;cursor:pointer;flex:none}
    .cp-hr-in{border:0;border-top:1px solid #999;margin:16px 0 12px}
    .cp-sub-title{font-size:11px;font-weight:700;color:#111;margin:0 0 6px}
    .cp-grid-tind{display:grid;grid-template-columns:2.2fr 1.1fr 1.6fr;gap:12px 22px}
    .cp-grid-solusi{display:grid;grid-template-columns:2.4fr 1.1fr 1.1fr 1.6fr;gap:12px 22px}

    .cp-save h3{margin:0 0 10px;font-size:18px;font-weight:700}
    .cp-btn{border:0;border-radius:4px;padding:7px 14px;font:inherit;font-size:10px;font-weight:700;color:#fff;cursor:pointer}
    .cp-btn.simpan{background:#16a34a}
    .cp-btn.reset{background:#c00000}
    .cp-btn.tarik{background:#7a0000}
    .cp-btn:hover{filter:brightness(.92)}

    .cp-errors{margin:0 0 16px;padding:12px 16px 12px 32px;border:1px solid #f0b4b4;border-radius:8px;background:#fdecec;color:#8a1010;font-size:12px}

    .rw-h{margin:0;font-size:20px;font-weight:700;color:#111}
    .rw-sub{margin:2px 0 14px;font-size:10px;color:#333}
    .rw-table{width:100%;border-collapse:separate;border-spacing:0;font-size:12px}
    .rw-table thead th{background:linear-gradient(90deg,#a81c1c,#5a0808);color:#fff;text-align:left;padding:11px 14px;font-weight:700;text-transform:uppercase;font-size:11px}
    .rw-table thead th:first-child{border-radius:8px 0 0 8px}
    .rw-table thead th:last-child{border-radius:0 8px 8px 0}
    .rw-body{background:#ececec;min-height:100px}
    .rw-table tbody td{padding:10px 14px;background:#ececec;border-top:1px solid #dcdcdc}
    .rw-empty{text-align:center;color:#777;padding:34px 0 !important}
    .rw-aksi{color:#c00000;font-weight:600}
</style>
@endpush

@section('konten')
@php
    $kj = ($pathway ?? null) ? $pathway->kunjungan->keyBy('urutan')->map(fn ($k) => $k->toArray())->all() : [];
    $autoHeader = $autoHeader ?? [];
    $autoKunjungan = $autoKunjungan ?? [];
    // Isian awal dari tab Diagnosa & Resep: hanya Kunjungan ke-1, dan hanya mengisi kolom yang masih kosong
    $val = function ($i, $k) use ($kj, $autoKunjungan) {
        $tersimpan = $kj[$i][$k] ?? '';
        if (blank($tersimpan) && $i == 1) {
            $tersimpan = $autoKunjungan[$k] ?? '';
        }
        return old("kunjungan.$i.$k", $tersimpan);
    };
    $hdr = function ($k) use ($pathway, $autoHeader) {
        $tersimpan = optional($pathway ?? null)->$k ?? '';
        if (blank($tersimpan)) {
            $tersimpan = $autoHeader[$k] ?? '';
        }
        return old($k, $tersimpan);
    };
    $riwayat = $riwayat ?? collect();
    $daftarDokter = $daftarDokter ?? collect();

    $aktif = 1;
    foreach ($errors->keys() as $key) {
        if (preg_match('/^kunjungan\.(\d+)/', $key, $m)) { $aktif = (int) $m[1]; break; }
    }
    $terisiAwal = count($kj);
@endphp

<form action="{{ url('pemeriksaan/' . $poli . '/' . $rm . '/clinical-pathway') }}" method="POST" id="form-cp" data-aktif="{{ $aktif }}" data-icd-url="{{ route('icd10.cari') }}" autocomplete="off">
    @csrf
    <input type="hidden" name="nama_pasien" value="{{ $pasien->nama ?? optional($pathway ?? null)->nama_pasien }}">

    @if ($errors->any())
        <ul class="cp-errors">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    @endif

    {{-- 1. Rencana Rawat Jalan --}}
    <section class="cp-card">
        <h2 class="cp-h">Rencana Rawat Jalan</h2>
        <div class="cp-grid3">
            <div class="cp-f"><label>Rencana Rawat Jalan (kali kunjungan) <i>*</i></label><input type="number" min="1" max="12" name="rencana_kunjungan" value="{{ $hdr('rencana_kunjungan') }}" placeholder="Contoh: 6 (maksimal 12)" required></div>
            <div class="cp-f"><label>Interval Kunjungan</label><input type="text" name="interval_kunjungan" value="{{ $hdr('interval_kunjungan') }}" placeholder="Contoh: 1"></div>
            <div class="cp-f"><label>Satuan Interval</label>
                <select name="satuan_interval">
                    <option value="">Pilih satuan (Hari / Minggu / Bulan)</option>
                    @foreach (['Hari', 'Minggu', 'Bulan'] as $opsi)
                        <option value="{{ $opsi }}" @selected(strcasecmp($hdr('satuan_interval'), $opsi) === 0)>{{ $opsi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="cp-f"><label>Diagnosis <i>*</i></label><input type="text" id="cp-diagnosis" name="diagnosis" data-cp-icd data-cp-kode="#cp-kode" autocomplete="off" value="{{ $hdr('diagnosis') }}" placeholder="Ketik kode atau nama penyakit (ICD-10), mis. I10 / hypertension" required></div>
            <div class="cp-f"><label>Kode ICD-10 <i>*</i></label><input type="text" id="cp-kode" name="kode_icd10" data-cp-icd data-cp-nama="#cp-diagnosis" autocomplete="off" value="{{ $hdr('kode_icd10') }}" placeholder="Contoh: I10" required></div>
        </div>
        <button type="button" class="cp-btn tarik" data-cp-tarik="{{ json_encode(['diagnosis' => $autoHeader['diagnosis'] ?? '', 'kode_icd10' => $autoHeader['kode_icd10'] ?? ''], JSON_UNESCAPED_UNICODE) }}">Ambil dari Diagnosa</button>
    </section>

    {{-- 2. Aktivitas Pelayanan --}}
    <section class="cp-card">
        <h2 class="cp-h">Aktivitas Pelayanan</h2>
        <p class="cp-sub">Pilih kunjungan untuk mengisi detail pelayanan. <span id="cp-terisi">{{ $terisiAwal }}</span> dari <span id="cp-total">1</span> kunjungan terisi.</p>

        <div class="cp-chips">
            @for ($i = 1; $i <= 12; $i++)
                <button type="button" class="cp-chip" data-chip="{{ $i }}" @if ($i > 1) hidden @endif><b>Kunjungan ke-{{ $i }}</b><small data-status>Kosong</small></button>
            @endfor
            <button type="button" class="cp-add" id="cp-add" title="Tambah kunjungan" aria-label="Tambah kunjungan">+</button>
        </div>

        @for ($i = 1; $i <= 12; $i++)
        <div class="cp-panel" data-panel="{{ $i }}" hidden>
            <div class="cp-kj-head"><strong>Kunjungan ke-{{ $i }}</strong></div>
            <hr class="cp-hr">

            <div class="cp-grid3" style="margin-bottom:12px">
                <div class="cp-f"><label>Nomor Kunjungan</label><input type="text" name="kunjungan[{{ $i }}][nomor]" value="{{ $val($i, 'nomor') }}" placeholder="Contoh: 1"></div>
                <div class="cp-f {{ $errors->has("kunjungan.$i.tanggal") ? 'err' : '' }}"><label>Tanggal <i>*</i></label><input type="date" name="kunjungan[{{ $i }}][tanggal]" value="{{ $val($i, 'tanggal') }}"></div>
                <div class="cp-f {{ $errors->has("kunjungan.$i.dpjp") ? 'err' : '' }}"><label>Dokter <i>*</i></label>
                    <select name="kunjungan[{{ $i }}][dpjp]">
                        <option value="">Pilih dokter</option>
                        @php $dpjpTerpilih = $val($i, 'dpjp'); @endphp
                        @foreach ($daftarDokter as $namaDokter)
                            <option value="{{ $namaDokter }}" @selected($dpjpTerpilih === $namaDokter)>{{ $namaDokter }}</option>
                        @endforeach
                        @if (filled($dpjpTerpilih) && ! $daftarDokter->contains($dpjpTerpilih))
                            <option value="{{ $dpjpTerpilih }}" selected>{{ $dpjpTerpilih }}</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="cp-f"><label>Catatan Kunjungan</label><textarea name="kunjungan[{{ $i }}][catatan]" placeholder="Tulis catatan kunjungan, mis. keluhan atau kondisi pasien">{{ $val($i, 'catatan') }}</textarea></div>

            {{-- Diagnosis (ICD-10) --}}
            <div class="cp-box">
                <div class="cp-box-head red">Diagnosis (ICD-10)</div>
                <div class="cp-box-body">
                    <div style="margin:0 0 12px"><button type="button" class="cp-btn tarik" data-cp-tarik="{{ json_encode(['diagnosis_utama' => $autoKunjungan['diagnosis_utama'] ?? '', 'kode_utama' => $autoKunjungan['kode_utama'] ?? '', 'diagnosis_penyerta' => $autoKunjungan['diagnosis_penyerta'] ?? ''], JSON_UNESCAPED_UNICODE) }}">Ambil dari Diagnosa</button></div>
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Diagnosis Utama</label><input type="text" name="kunjungan[{{ $i }}][diagnosis_utama]" data-cp-icd data-cp-kode="kode_utama" autocomplete="off" value="{{ $val($i, 'diagnosis_utama') }}" placeholder="Ketik kode atau nama penyakit (ICD-10)"></div>
                        <div class="cp-f"><label>Kode ICD-10</label><input type="text" name="kunjungan[{{ $i }}][kode_utama]" data-cp-icd data-cp-nama="diagnosis_utama" autocomplete="off" value="{{ $val($i, 'kode_utama') }}" placeholder="Contoh: I10"></div>
                        <div class="cp-f"><label>Diagnosis Penyerta</label><input type="text" name="kunjungan[{{ $i }}][diagnosis_penyerta]" data-cp-icd data-cp-kode="kode_penyerta" autocomplete="off" value="{{ $val($i, 'diagnosis_penyerta') }}" placeholder="Ketik kode atau nama diagnosis penyerta (jika ada)"></div>
                        <div class="cp-f"><label>Kode ICD-10</label><input type="text" name="kunjungan[{{ $i }}][kode_penyerta]" data-cp-icd data-cp-nama="diagnosis_penyerta" autocomplete="off" value="{{ $val($i, 'kode_penyerta') }}" placeholder="Contoh: E11"></div>
                        <div class="cp-f"><label>Komplikasi</label><input type="text" name="kunjungan[{{ $i }}][komplikasi]" data-cp-icd data-cp-kode="kode_komplikasi" autocomplete="off" value="{{ $val($i, 'komplikasi') }}" placeholder="Ketik kode atau nama komplikasi (jika ada)"></div>
                        <div class="cp-f"><label>Kode ICD-10</label><input type="text" name="kunjungan[{{ $i }}][kode_komplikasi]" data-cp-icd data-cp-nama="komplikasi" autocomplete="off" value="{{ $val($i, 'kode_komplikasi') }}" placeholder="Contoh: I50"></div>
                    </div>
                </div>
            </div>

            {{-- Pemeriksaan DPJP & Konsultasi --}}
            <div class="cp-box">
                <div class="cp-box-head red">Pemeriksaan DPJP &amp; Konsultasi</div>
                <div class="cp-box-body">
                    @php
                        $pemDpjp = $val($i, 'pemeriksaan_dpjp');
                        $pemDilakukan = in_array($pemDpjp, ['Dilakukan', 'Ya'], true);
                        $pemTidak = $pemDpjp === 'Tidak dilakukan';
                    @endphp
                    <div class="cp-check-group">
                        <label class="cp-check"><input type="radio" name="kunjungan[{{ $i }}][pemeriksaan_dpjp]" value="Dilakukan" @checked($pemDilakukan)><span>Dilakukan pemeriksaan</span></label>
                        <label class="cp-check"><input type="radio" name="kunjungan[{{ $i }}][pemeriksaan_dpjp]" value="Tidak dilakukan" @checked($pemTidak)><span>Tidak dilakukan pemeriksaan</span></label>
                    </div>
                    <hr class="cp-hr-in">
                    <div class="cp-sub-title">KONSULTASI</div>
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Konsultasi / Bagian</label><input type="text" name="kunjungan[{{ $i }}][konsultasi_bagian]" value="{{ $val($i, 'konsultasi_bagian') }}" placeholder="Contoh: Poli Penyakit Dalam"></div>
                        <div class="cp-f"><label>Catatan</label><input type="text" name="kunjungan[{{ $i }}][konsultasi_catatan]" value="{{ $val($i, 'konsultasi_catatan') }}" placeholder="Tulis hasil atau catatan konsultasi"></div>
                    </div>
                </div>
            </div>

            {{-- Pemeriksaan Penunjang (ICD-9-CM) --}}
            <div class="cp-box">
                <div class="cp-box-head red">Pemeriksaan Penunjang (ICD-9-CM)</div>
                <div class="cp-box-body">
                    <div class="cp-grid-tind">
                        <div class="cp-f"><label>Jenis Pemeriksaan</label><input type="text" name="kunjungan[{{ $i }}][tindakan_nama]" value="{{ $val($i, 'tindakan_nama') }}" placeholder="Tulis bebas, mis. EKG" autocomplete="off"></div>
                        <div class="cp-f"><label>Kode ICD-9-CM</label><input type="text" name="kunjungan[{{ $i }}][tindakan_kode]" value="{{ $val($i, 'tindakan_kode') }}" placeholder="Ketik kode atau nama, mis. 89.52" data-cp-icd data-cp-gabung data-cp-url="{{ route('icd9.cari') }}" autocomplete="off"></div>
                </div>
            </div>

            {{-- Obat-obatan --}}
            <div class="cp-box">
                <div class="cp-box-head red">Obat-obatan</div>
                <div class="cp-box-body">
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Nama Obat</label><input type="text" name="kunjungan[{{ $i }}][obat_nama]" value="{{ $val($i, 'obat_nama') }}" placeholder="Ketik nama obat"></div>
                        <div class="cp-f"><label>Keterangan</label><input type="text" name="kunjungan[{{ $i }}][obat_keterangan]" value="{{ $val($i, 'obat_keterangan') }}" placeholder="Contoh: 3x1 sesudah makan, 10 tablet"></div>
                    </div>
                </div>
            </div>

            {{-- Pendidikan dan Promosi Kesehatan --}}
            <div class="cp-box">
                <div class="cp-box-head red">Pendidikan dan Promosi Kesehatan</div>
                <div class="cp-box-body">
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Materi Edukasi</label><input type="text" name="kunjungan[{{ $i }}][edukasi_materi]" value="{{ $val($i, 'edukasi_materi') }}" placeholder="Contoh: Diet rendah garam"></div>
                        <div class="cp-f"><label>Keterangan</label><input type="text" name="kunjungan[{{ $i }}][edukasi_keterangan]" value="{{ $val($i, 'edukasi_keterangan') }}" placeholder="Tulis keterangan edukasi yang diberikan"></div>
                    </div>
                </div>
            </div>

            {{-- Solusi Tindak Lanjut --}}
            <div class="cp-box">
                <div class="cp-box-head red">Solusi Tindak Lanjut</div>
                <div class="cp-box-body">
                    <div class="cp-grid-solusi">
                        <div class="cp-f"><label>Tindakan / Rencana Tindak Lanjut</label><input type="text" name="kunjungan[{{ $i }}][solusi_rencana]" value="{{ $val($i, 'solusi_rencana') }}" placeholder="Tulis rencana tindak lanjut"></div>
                        <div class="cp-f"><label>Penanggung Jawab</label><input type="text" name="kunjungan[{{ $i }}][solusi_pj]" value="{{ $val($i, 'solusi_pj') }}" placeholder="Contoh: Dokter / perawat penanggung jawab"></div>
                        <div class="cp-f"><label>Target Waktu</label><input type="date" name="kunjungan[{{ $i }}][solusi_target]" value="{{ $val($i, 'solusi_target') }}"></div>
                        <div class="cp-f"><label>Catatan</label><input type="text" name="kunjungan[{{ $i }}][solusi_catatan]" value="{{ $val($i, 'solusi_catatan') }}" placeholder="Tulis catatan tindak lanjut"></div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </section>

    {{-- 3. Simpan --}}
    <section class="cp-card cp-save">
        <h3>Simpan Clinical Pathway</h3>
        <button type="submit" class="cp-btn simpan">Simpan Clinical Pathway</button>
        <button type="reset" class="cp-btn reset" id="cp-reset">Reset</button>
    </section>
</form>

{{-- 4. Riwayat --}}
<section class="cp-card">
    <h2 class="rw-h">Riwayat Clinical Pathway</h2>
    <table class="rw-table">
        <thead>
            <tr><th>No. RM</th><th>Nama Pasien</th><th>Diagnosis Utama</th><th>Tanggal Pathway</th><th>Progres Kunjungan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($riwayat as $r)
                <tr>
                    <td>{{ $r->no_rm }}</td>
                    <td>{{ $r->nama_pasien }}</td>
                    <td>{{ $r->diagnosis }}</td>
                    <td>{{ optional($r->updated_at)->format('d/m/Y') }}</td>
                    <td>{{ $r->terisi }}/{{ $r->rencana_kunjungan }}</td>
                    <td>{{ $r->status }}</td>
                    <td><a class="rw-aksi" href="{{ url('pemeriksaan/' . $r->poli . '/' . $r->no_rm . '/clinical-pathway') }}">Buka</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="rw-empty" style="height:100px">Belum ada Clinical Pathway tersimpan.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/cp-icd10.js') }}"></script>
<script>
(function () {
    var form = document.getElementById('form-cp');
    var chips = form.querySelectorAll('[data-chip]');
    var panels = form.querySelectorAll('[data-panel]');
    var counter = document.getElementById('cp-terisi');
    var total = document.getElementById('cp-total');
    var addBtn = document.getElementById('cp-add');
    var MAX = chips.length;
    var visible = 1;

    function setVisible(n) {
        visible = Math.min(MAX, Math.max(1, n));
        chips.forEach(function (c, i) { c.hidden = (i + 1) > visible; });
        addBtn.hidden = visible >= MAX;
        total.textContent = visible;
    }

    function filled(p) {
        var f = p.querySelectorAll('input, textarea');
        for (var i = 0; i < f.length; i++) {
            if (f[i].type === 'checkbox' || f[i].type === 'radio') { if (f[i].checked) return true; continue; }
            if (f[i].value.trim() !== '') return true;
        }
        return false;
    }
    // Kunjungan dianggap "terisi" kalau Tanggal dan Dokter (kolom wajib) sudah diisi
    function lengkap(p) {
        var t = p.querySelector('[name$="[tanggal]"]');
        var d = p.querySelector('[name$="[dpjp]"]');
        return !!(t && t.value.trim() !== '' && d && d.value.trim() !== '');
    }
    function refresh() {
        var n = 0;
        panels.forEach(function (p, i) {
            var ok = lengkap(p);
            if (ok) n++;
            chips[i].querySelector('[data-status]').textContent = ok ? 'Sudah terisi' : 'Kosong';
            chips[i].classList.toggle('terisi', ok);
        });
        counter.textContent = n;
    }
    function show(no) {
        panels.forEach(function (p) { p.hidden = p.getAttribute('data-panel') !== String(no); });
        chips.forEach(function (c) { c.classList.toggle('on', c.getAttribute('data-chip') === String(no)); });
    }

    form.addEventListener('click', function (e) {
        var chip = e.target.closest('[data-chip]');
        if (chip) show(chip.getAttribute('data-chip'));
    });
    addBtn.addEventListener('click', function () {
        setVisible(visible + 1);
        show(visible);
    });
    form.addEventListener('input', refresh);
    form.addEventListener('change', refresh);
    document.getElementById('cp-reset').addEventListener('click', function () {
        setTimeout(function () { setVisible(1); refresh(); show(1); }, 0);
    });

    // Tampilkan kunjungan sampai kunjungan terakhir yang sudah terisi / yang error
    var aktif = parseInt(form.getAttribute('data-aktif') || 1, 10);
    var terakhir = 1;
    panels.forEach(function (p, i) { if (i > 0 && filled(p)) terakhir = i + 1; });
    setVisible(Math.max(terakhir, aktif));

    refresh();
    show(aktif);
})();
</script>
@endpush