@extends('layouts.pemeriksaan', ['tabAktif' => 'Clinical Pathway'])

@push('styles')
<style>
    .cp-card{border:2px solid #3b82f6;border-radius:10px;padding:20px 22px 24px;margin-bottom:18px;background:#fff}
    .cp-h{margin:0 0 4px;font-size:15px;font-weight:700;color:#ed1111}
    .cp-sub{margin:0 0 16px;font-size:13px;color:#222}
    .cp-grid3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px 22px;margin-bottom:16px}
    .cp-grid2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 44px}
    .cp-f{display:flex;flex-direction:column;gap:6px;min-width:0}
    .cp-f label{font-size:11px;font-weight:600;color:#111}
    .cp-f label i{color:#c00000;font-style:normal}
    .cp-f input,.cp-f textarea{width:100%;height:38px;border:1px solid #333;border-radius:5px;padding:0 12px;font:inherit;font-size:12px;background:#fff;color:#111}
    .cp-f textarea{height:84px;padding:10px 12px;resize:vertical}
    .cp-f input::placeholder,.cp-f textarea::placeholder{color:#8a8a8a;font-size:11px}
    .cp-f input:focus,.cp-f textarea:focus{outline:2px solid #c00000;outline-offset:1px}
    .cp-f.err input{border-color:#c00000}

    .cp-chips{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 20px}
    .cp-chip{width:46px;padding:4px 0 3px;border:1px solid #333;border-radius:5px;background:#fff;font:inherit;text-align:center;cursor:pointer;line-height:1.2}
    .cp-chip b{display:block;font-size:11px;font-weight:600}
    .cp-chip small{display:block;font-size:8px;color:#777}
    .cp-chip.terisi small{color:#14803a;font-weight:600}
    .cp-chip.on{border-color:#c00000;background:#fdecec;box-shadow:0 0 0 1px #c00000}

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
    .cp-hr-in{border:0;border-top:1px solid #999;margin:16px 0 12px}
    .cp-sub-title{font-size:11px;font-weight:700;color:#111;margin:0 0 6px}
    .cp-grid-tind{display:grid;grid-template-columns:2.2fr 1.1fr 1.6fr;gap:12px 22px}
    .cp-grid-solusi{display:grid;grid-template-columns:2.4fr 1.1fr 1.1fr 1.6fr;gap:12px 22px}

    .cp-save h3{margin:0 0 10px;font-size:18px;font-weight:700}
    .cp-btn{border:0;border-radius:4px;padding:7px 14px;font:inherit;font-size:10px;font-weight:700;color:#fff;cursor:pointer}
    .cp-btn.simpan{background:#00b512}
    .cp-btn.reset{background:#c00000}
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
    $val = fn ($i, $k) => old("kunjungan.$i.$k", $kj[$i][$k] ?? '');
    $hdr = fn ($k) => old($k, optional($pathway ?? null)->$k ?? '');
    $riwayat = $riwayat ?? collect();

    $aktif = 1;
    foreach ($errors->keys() as $key) {
        if (preg_match('/^kunjungan\.(\d+)/', $key, $m)) { $aktif = (int) $m[1]; break; }
    }
    $terisiAwal = count($kj);
@endphp

<form action="{{ url('pemeriksaan/' . $poli . '/' . $rm . '/clinical-pathway') }}" method="POST" id="form-cp" data-aktif="{{ $aktif }}">
    @csrf
    <input type="hidden" name="nama_pasien" value="{{ $pasien->nama ?? 'Budi Santoso' }}">

    @if ($errors->any())
        <ul class="cp-errors">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    @endif

    {{-- 1. Rencana Rawat Jalan --}}
    <section class="cp-card">
        <h2 class="cp-h">Rencana Rawat Jalan</h2>
        <p class="cp-sub">Rencana jumlah kunjungan, jarak antar kunjungan, dan diagnosis yang menjadi dasar Clinical Pathway.</p>
        <div class="cp-grid3">
            <div class="cp-f"><label>Rencana Rawat Jalan (kali kunjungan) <i>*</i></label><input type="number" min="1" max="12" name="rencana_kunjungan" value="{{ $hdr('rencana_kunjungan') }}" placeholder="misal 6" required></div>
            <div class="cp-f"><label>Interval Kunjungan</label><input type="text" name="interval_kunjungan" value="{{ $hdr('interval_kunjungan') }}" placeholder="Interval kunjungan"></div>
            <div class="cp-f"><label>Satuan Interval</label><input type="text" name="satuan_interval" value="{{ $hdr('satuan_interval') }}" placeholder="Interval satuan"></div>
            <div class="cp-f"><label>Diagnosis <i>*</i></label><input type="text" name="diagnosis" value="{{ $hdr('diagnosis') }}" placeholder="Cari diagnosis" required></div>
            <div class="cp-f"><label>Kode ICD-10 <i>*</i></label><input type="text" name="kode_icd10" value="{{ $hdr('kode_icd10') }}" placeholder="Cari diagnosis" required></div>
        </div>
    </section>

    {{-- 2. Aktivitas Pelayanan --}}
    <section class="cp-card">
        <h2 class="cp-h">Aktivitas Pelayanan</h2>
        <p class="cp-sub">Pilih kunjungan untuk mengisi detail pelayanan. <span id="cp-terisi">{{ $terisiAwal }}</span> dari 12 kunjungan terisi.</p>

        <div class="cp-chips">
            @for ($i = 1; $i <= 12; $i++)
                <button type="button" class="cp-chip" data-chip="{{ $i }}"><b>{{ $i }}</b><small data-status>Kosong</small></button>
            @endfor
        </div>

        @for ($i = 1; $i <= 12; $i++)
        <div class="cp-panel" data-panel="{{ $i }}" hidden>
            <div class="cp-kj-head"><strong>Kunjungan {{ $i }}</strong><span>dari maksimal 12</span></div>
            <div class="cp-kj-info" data-info>Tanggal belum diisi • DPJP belum dipilih</div>
            <hr class="cp-hr">

            <div class="cp-grid3" style="margin-bottom:12px">
                <div class="cp-f"><label>Nomor Kunjungan</label><input type="text" name="kunjungan[{{ $i }}][nomor]" value="{{ $val($i, 'nomor') }}"></div>
                <div class="cp-f {{ $errors->has("kunjungan.$i.tanggal") ? 'err' : '' }}"><label>Tanggal <i>*</i></label><input type="date" name="kunjungan[{{ $i }}][tanggal]" value="{{ $val($i, 'tanggal') }}"></div>
                <div class="cp-f {{ $errors->has("kunjungan.$i.dpjp") ? 'err' : '' }}"><label>Kode / Nama DPJP <i>*</i></label><input type="text" name="kunjungan[{{ $i }}][dpjp]" value="{{ $val($i, 'dpjp') }}"></div>
            </div>
            <div class="cp-f"><label>Catatan Kunjungan</label><textarea name="kunjungan[{{ $i }}][catatan]">{{ $val($i, 'catatan') }}</textarea></div>

            {{-- Diagnosis (ICD-10) --}}
            <div class="cp-box">
                <div class="cp-box-head red">Diagnosis (ICD-10)</div>
                <div class="cp-box-body">
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Diagnosis Utama</label><input type="text" name="kunjungan[{{ $i }}][diagnosis_utama]" value="{{ $val($i, 'diagnosis_utama') }}"></div>
                        <div class="cp-f"><label>Kode ICD-10</label><input type="text" name="kunjungan[{{ $i }}][kode_utama]" value="{{ $val($i, 'kode_utama') }}"></div>
                        <div class="cp-f"><label>Diagnosis Penyerta</label><input type="text" name="kunjungan[{{ $i }}][diagnosis_penyerta]" value="{{ $val($i, 'diagnosis_penyerta') }}"></div>
                        <div class="cp-f"><label>Kode ICD-10</label><input type="text" name="kunjungan[{{ $i }}][kode_penyerta]" value="{{ $val($i, 'kode_penyerta') }}"></div>
                        <div class="cp-f"><label>Komplikasi</label><input type="text" name="kunjungan[{{ $i }}][komplikasi]" value="{{ $val($i, 'komplikasi') }}"></div>
                        <div class="cp-f"><label>Kode ICD-10</label><input type="text" name="kunjungan[{{ $i }}][kode_komplikasi]" value="{{ $val($i, 'kode_komplikasi') }}"></div>
                    </div>
                </div>
            </div>

            {{-- Pemeriksaan DPJP & Konsultasi --}}
            <div class="cp-box">
                <div class="cp-box-head red">Pemeriksaan DPJP &amp; Konsultasi</div>
                <div class="cp-box-body">
                    <div class="cp-f cp-f-small"><label>Pemeriksaan DPJP</label><input type="text" name="kunjungan[{{ $i }}][pemeriksaan_dpjp]" value="{{ $val($i, 'pemeriksaan_dpjp') }}"></div>
                    <hr class="cp-hr-in">
                    <div class="cp-sub-title">KONSULTASI</div>
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Konsultasi / Bagian</label><input type="text" name="kunjungan[{{ $i }}][konsultasi_bagian]" value="{{ $val($i, 'konsultasi_bagian') }}"></div>
                        <div class="cp-f"><label>Catatan</label><input type="text" name="kunjungan[{{ $i }}][konsultasi_catatan]" value="{{ $val($i, 'konsultasi_catatan') }}"></div>
                    </div>
                </div>
            </div>

            {{-- Pemeriksaan Penunjang (ICD-9-CM) --}}
            <div class="cp-box">
                <div class="cp-box-head red">Pemeriksaan Penunjang (ICD-9-CM)</div>
                <div class="cp-box-body">
                    <p>Kode mengacu pada ICD-9-CM.</p>
                    <div class="cp-grid-tind">
                        <div class="cp-f"><label>Jenis Pemeriksaan</label><input type="text" name="kunjungan[{{ $i }}][tindakan_nama]" value="{{ $val($i, 'tindakan_nama') }}"></div>
                        <div class="cp-f"><label>Kode ICD-9-CM</label><input type="text" name="kunjungan[{{ $i }}][tindakan_kode]" value="{{ $val($i, 'tindakan_kode') }}"></div>
                        <div class="cp-f"><label>Catatan / Hasil</label><input type="text" name="kunjungan[{{ $i }}][tindakan_catatan]" value="{{ $val($i, 'tindakan_catatan') }}"></div>
                    </div>
                </div>
            </div>

            {{-- Obat-obatan --}}
            <div class="cp-box">
                <div class="cp-box-head red">Obat-obatan</div>
                <div class="cp-box-body">
                    <p>Nama obat dapat dipilih dari Data Master Obat SIP-REK.</p>
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Nama Obat</label><input type="text" name="kunjungan[{{ $i }}][obat_nama]" value="{{ $val($i, 'obat_nama') }}"></div>
                        <div class="cp-f"><label>Keterangan</label><input type="text" name="kunjungan[{{ $i }}][obat_keterangan]" value="{{ $val($i, 'obat_keterangan') }}"></div>
                    </div>
                </div>
            </div>

            {{-- Pendidikan dan Promosi Kesehatan --}}
            <div class="cp-box">
                <div class="cp-box-head red">Pendidikan dan Promosi Kesehatan</div>
                <div class="cp-box-body">
                    <p>Hospital Health Promotion.</p>
                    <div class="cp-grid2">
                        <div class="cp-f"><label>Materi Edukasi</label><input type="text" name="kunjungan[{{ $i }}][edukasi_materi]" value="{{ $val($i, 'edukasi_materi') }}"></div>
                        <div class="cp-f"><label>Keterangan</label><input type="text" name="kunjungan[{{ $i }}][edukasi_keterangan]" value="{{ $val($i, 'edukasi_keterangan') }}"></div>
                    </div>
                </div>
            </div>

            {{-- Cost --}}
            <div class="cp-box">
                <div class="cp-box-head red">Cost</div>
                <div class="cp-box-body cost"><textarea name="kunjungan[{{ $i }}][cost]" aria-label="Cost">{{ $val($i, 'cost') }}</textarea></div>
            </div>

            {{-- Solusi Tindak Lanjut --}}
            <div class="cp-box">
                <div class="cp-box-head red">Solusi Tindak Lanjut</div>
                <div class="cp-box-body">
                    <p>Rencana perbaikan atas varians atau tindak lanjut pelayanan.</p>
                    <div class="cp-grid-solusi">
                        <div class="cp-f"><label>Tindakan / Rencana Tindak Lanjut</label><input type="text" name="kunjungan[{{ $i }}][solusi_rencana]" value="{{ $val($i, 'solusi_rencana') }}"></div>
                        <div class="cp-f"><label>Penanggung Jawab</label><input type="text" name="kunjungan[{{ $i }}][solusi_pj]" value="{{ $val($i, 'solusi_pj') }}"></div>
                        <div class="cp-f"><label>Target Waktu</label><input type="date" name="kunjungan[{{ $i }}][solusi_target]" value="{{ $val($i, 'solusi_target') }}"></div>
                        <div class="cp-f"><label>Catatan</label><input type="text" name="kunjungan[{{ $i }}][solusi_catatan]" value="{{ $val($i, 'solusi_catatan') }}"></div>
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
    <p class="rw-sub">Seluruh Clinical Pathway Rawat Jalan yang tersimpan di SIP-REK</p>
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
<script>
(function () {
    var form = document.getElementById('form-cp');
    var chips = form.querySelectorAll('[data-chip]');
    var panels = form.querySelectorAll('[data-panel]');
    var counter = document.getElementById('cp-terisi');

    function filled(p) {
        var f = p.querySelectorAll('input, textarea');
        for (var i = 0; i < f.length; i++) { if (f[i].value.trim() !== '') return true; }
        return false;
    }
    function info(p) {
        var t = p.querySelector('[name$="[tanggal]"]').value;
        var d = p.querySelector('[name$="[dpjp]"]').value;
        p.querySelector('[data-info]').textContent =
            (t ? 'Tanggal ' + t : 'Tanggal belum diisi') + ' • ' + (d ? 'DPJP ' + d : 'DPJP belum dipilih');
    }
    function refresh() {
        var n = 0;
        panels.forEach(function (p, i) {
            var ok = filled(p);
            if (ok) n++;
            chips[i].querySelector('[data-status]').textContent = ok ? 'Terisi' : 'Kosong';
            chips[i].classList.toggle('terisi', ok);
            info(p);
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
    form.addEventListener('input', refresh);
    document.getElementById('cp-reset').addEventListener('click', function () {
        setTimeout(function () { refresh(); show(1); }, 0);
    });

    refresh();
    show(form.getAttribute('data-aktif') || 1);
})();
</script>
@endpush