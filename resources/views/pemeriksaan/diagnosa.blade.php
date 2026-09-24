{{--
    Halaman lengkap tab Diagnosa (sidebar + header + banner pasien + tab + form).
    CSS & JS sudah di dalam file ini, jadi tidak perlu file lain selain diagnosa-row.blade.php.

    Variabel dari controller (semua opsional):
      $pasien   objek/array pasien: nama, no_rm, nik, jenis_kelamin, tanggal_lahir, usia
      $rekam    rekam medis kunjungan ini (diagnosis_utama, kode_icd10,
                diagnosis_sekunder, tindakan) -- dipakai untuk isi ulang form saat edit
      $action   URL simpan
--}}
@php
    $utama = old('diagnosis_utama', $rekam->diagnosis_utama ?? null);
    $kodeIcd10 = old('kode_icd10', $rekam->kode_icd10 ?? null);

    $sekunder = old(
        'diagnosis_sekunder',
        isset($rekam) && $rekam->diagnosis_sekunder ? explode(', ', $rekam->diagnosis_sekunder) : []
    );

    $tindakanList = old(
        'tindakan',
        isset($rekam) && $rekam->tindakan ? explode(', ', $rekam->tindakan) : []
    );

    if (count($sekunder) === 0) { $sekunder = [null]; }
    if (count($tindakanList) === 0) { $tindakanList = [null]; }

    $poli = request()->segment(2) ?? 'umum';
    $rm = request()->segment(3) ?? '';
    $base = url('pemeriksaan/' . $poli . '/' . $rm);
    // Ubah alamat tab sesuai route project Anda
    $tabs = [
        ['Assesment', $base . '/asesmen'],
        ['SOAP', $base . '/soap'],
        ['Diagnosa', $base . '/diagnosa'],
        ['Resep', $base . '/resep'],
        ['Clinical Phatway', $base . '/clinical-pathway'],
        ['Penunjang', $base . '/penunjang'],
        ['Rujukan', $base . '/rujukan'],
        ['autentikasi', $base . '/autentikasi'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnosa - Klinik Merah Putih</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <style>
        *{box-sizing:border-box}
        body{margin:0;height:100vh;display:flex;font-family:'Poppins',system-ui,sans-serif;font-size:14px;color:#111;background:#fff}
        a{color:inherit;text-decoration:none}

        /* Sidebar */
        .sb{width:250px;flex-shrink:0;background:linear-gradient(180deg,#7a0000 0%,#420000 45%,#2a0000 100%);color:#fff;display:flex;flex-direction:column;padding:26px 22px 28px}
        .sb-brand{display:flex;align-items:center;gap:12px;padding:0 4px 22px;border-bottom:1px solid rgba(255,255,255,.35);margin-bottom:38px}
        .sb-logo{width:46px;height:46px;border-radius:10px;background:linear-gradient(180deg,#c21b1b,#7d0808);display:flex;align-items:center;justify-content:center}
        .sb-brand span{font-size:11px}
        .sb a{display:flex;align-items:center;gap:14px;padding:11px 14px;border-radius:12px;font-size:13px;font-weight:600;margin-bottom:8px}
        .sb a.sub{padding-left:52px}
        .sb a.active{background:#b01515}
        .sb-space{flex:1}
        .sb-out{border-top:1px solid rgba(255,255,255,.35);padding-top:20px}

        /* Konten */
        .main{flex:1;min-width:0;display:flex;flex-direction:column;overflow-y:auto}
        .top{height:66px;flex-shrink:0;display:flex;align-items:center;justify-content:space-between;padding:0 34px;border-bottom:3px solid #e3e3e3;box-shadow:0 2px 3px rgba(0,0,0,.08)}
        .top-title{display:flex;align-items:center;gap:10px;color:#c00000;font-weight:700;font-size:15px}
        .top-admin{display:flex;align-items:center;gap:10px;color:#c00000;font-weight:700;font-size:14px}
        .content{padding:22px 34px 40px}
        .back{display:flex;align-items:center;gap:14px;font-size:13px;font-weight:600;margin:0 0 18px 4px}

        /* Banner pasien */
        .pasien{display:flex;align-items:center;gap:28px;border-radius:14px;background:linear-gradient(90deg,#a81c1c 0%,#8a1212 55%,#5a0808 100%);color:#fff;padding:20px 26px}
        .pasien-nama{width:210px;flex-shrink:0}
        .pasien-nama small{display:block;font-size:12px;font-weight:600}
        .pasien-nama strong{display:block;font-size:22px;font-weight:700;letter-spacing:.2px}
        .pasien-grid{flex:1;display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:22px}
        .pasien-grid div{text-align:center;font-size:12px;font-weight:600}
        .pasien-grid span{display:block;height:24px;margin-top:6px;background:#fff;border-radius:9px;color:#333;font-weight:500;line-height:24px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:0 8px}

        /* Tab */
        .tabs{display:flex;align-items:center;justify-content:space-between;margin:18px 4px 14px;font-size:12px;font-weight:600}
        .tabs a{padding:7px 20px}
        .tabs a.on{background:linear-gradient(90deg,#b01212,#600000);color:#fff;border-radius:999px}

        /* Kartu form */
        .dx-card{border:1px solid #dedede;border-radius:12px;padding:30px 34px 60px;min-height:440px;background:#fff}
        .dx-title{margin:0 0 30px;font-size:22px;font-weight:700;color:#c00000}
        .dx-group{display:flex;flex-direction:column;gap:10px;min-width:0}
        .dx-utama{margin-bottom:38px}
        .dx-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:30px 44px;align-items:start}
        .dx-head{display:flex;align-items:center;justify-content:space-between;min-height:28px}
        .dx-label{font-size:13px;font-weight:600;color:#111}
        .dx-select{height:46px;width:100%;border:1px solid #a9a9a9;border-radius:7px;padding:0 24px;font:inherit;font-size:13px;font-weight:500;background:#fff;color:#222}
        .dx-select:invalid{color:#8a8a8a}
        .dx-select option{color:#222}
        .dx-select:focus{outline:2px solid #c00000;outline-offset:1px}
        .dx-list{display:flex;flex-direction:column;gap:12px}
        .dx-row{display:flex;align-items:center;gap:8px}
        .dx-row .dx-select{flex:1}
        .dx-btn-tambah{height:28px;padding:0 14px;border:0;border-radius:7px;background:#b71c1c;color:#fff;font:inherit;font-size:11px;font-weight:600;cursor:pointer}
        .dx-btn-tambah:hover{background:#9c1515}
        .dx-btn-hapus{width:46px;height:46px;flex-shrink:0;border:1px solid #b71c1c;border-radius:7px;background:#fff;color:#b71c1c;font:inherit;font-size:20px;line-height:1;cursor:pointer}
        .dx-btn-hapus:hover{background:#fdecec}
    </style>
</head>
<body>
    <aside class="sb">
        <div class="sb-brand">
            <div class="sb-logo">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M12 21s-7-4.6-9.2-9.1C1.3 8.6 3.2 5 6.6 5c2 0 3.7 1.1 5.4 3.1C13.7 6.1 15.4 5 17.4 5c3.4 0 5.3 3.6 3.800 6.900C19 16.400 12 21 12 21z"/></svg>
            </div>
            <span>Klinik Merah Putih</span>
        </div>

        <a href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path d="M12 3 2 12h3v8h5v-5h4v5h5v-8h3z"/></svg>Dashboard</a>
        <a href="#"><svg width="18" height="18" viewBox="0 0 24 24" fill="#fff"><path fill-rule="evenodd" d="M9 2h6v2h3a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3V2zm1 1.5V5h4V3.500h-4zM8 11h8v1.6H8V11zm0 3.500h8V16H8v-1.5z"/></svg>Pendaftaran</a>
        <a href="#" class="active"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8.5c-1-1.6-3.6-1.4-3.6.7 0 1.6 2 2.7 3.6 4 1.6-1.3 3.6-2.4 3.6-4 0-2.1-2.6-2.300-3.6-.7z"/><path d="M3 15h3.500l3 2h4.500a1.6 1.6 0 0 0 0-3.2H11M6.5 15v5H3M14 17l5-1.800a1.6 1.6 0 0 1 1.5 2.800L14 21l-4-1"/></svg>Pemeriksaan</a>
        <a href="#" class="sub">Penunjang</a>
        <a href="#" class="sub">Farmasi</a>
        <a href="#" class="sub">Rekam Medis</a>
        <a href="#" class="sub">Laporan</a>

        <div class="sb-space"></div>
        <div class="sb-out"><a href="#" class="sub" style="padding-left:14px;margin:0">Keluar</a></div>
    </aside>

    <div class="main">
        <header class="top">
            <div class="top-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#c00000"><path d="M12 3 2 12h3v8h5v-5h4v5h5v-8h3z"/></svg>
                Pemeriksaan
            </div>
            <div class="top-admin">
                <svg width="26" height="26" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#c00000"/><circle cx="12" cy="9.5" r="3.2" fill="#fff"/><path d="M5.5 19c1.2-3.2 3.6-4.6 6.5-4.6s5.3 1.4 6.5 4.6a10 10 0 0 1-13 0z" fill="#fff"/></svg>
                Admin
            </div>
        </header>

        <div class="content">
            <a href="{{ url('pemeriksaan/' . $poli) }}" class="back"><span>&larr;</span>Kembali ke Antrian</a>

            <section class="pasien">
                <div class="pasien-nama">
                    <small>Identitas Pasien</small>
                    <strong>{{ strtoupper($pasien->nama ?? 'Budi Santoso') }}</strong>
                </div>
                <div class="pasien-grid">
                    <div>No.RM<span>{{ $pasien->no_rm ?? $rm }}</span></div>
                    <div>NIK<span>{{ $pasien->nik ?? '' }}</span></div>
                    <div>Jenis Kelamin<span>{{ $pasien->jenis_kelamin ?? '' }}</span></div>
                    <div>Tanggal Lahir<span>{{ $pasien->tanggal_lahir ?? '' }}</span></div>
                    <div>Usia<span>{{ $pasien->usia ?? '' }}</span></div>
                </div>
            </section>

            <nav class="tabs">
                @foreach ($tabs as [$nama, $link])
                    <a href="{{ $link }}" class="{{ $nama === 'Diagnosa' ? 'on' : '' }}">{{ $nama }}</a>
                @endforeach
            </nav>

            <form action="{{ $action ?? '#' }}" method="POST" class="dx-card" id="form-diagnosa">
                @csrf
                <h2 class="dx-title">DIAGNOSIS</h2>

                <div class="dx-group dx-utama">
                    <label class="dx-label" for="diagnosis_utama">Diagnosis Utama</label>
                    <input type="text" id="diagnosis_utama" name="diagnosis_utama"
                           class="dx-select" placeholder="isi diagnosis utama, contoh: Infeksi saluran pernapasan akut"
                           value="{{ $utama }}" required>
                </div>

                <div class="dx-group">
                    <label class="dx-label" for="kode_icd10">Kode ICD-10 (opsional)</label>
                    <input type="text" id="kode_icd10" name="kode_icd10"
                           class="dx-select" placeholder="contoh: J06.9"
                           value="{{ $kodeIcd10 }}">
                </div>

                <div class="dx-grid">
                    <div class="dx-group" data-dx-section>
                        <div class="dx-head">
                            <span class="dx-label">Diagnosis Sekunder</span>
                            <button type="button" class="dx-btn-tambah" data-add>+ Tambah</button>
                        </div>
                        <div class="dx-list" data-list>
                            @foreach ($sekunder as $nilai)
                                @include('pemeriksaan.diagnosa-row', ['name' => 'diagnosis_sekunder', 'label' => 'Diagnosis Sekunder', 'placeholder' => 'isi diagnosis sekunder', 'selected' => $nilai, 'removable' => ! $loop->first])
                            @endforeach
                        </div>
                        <template data-template>
                            @include('pemeriksaan.diagnosa-row', ['name' => 'diagnosis_sekunder', 'label' => 'Diagnosis Sekunder', 'placeholder' => 'isi diagnosis sekunder', 'removable' => true])
                        </template>
                    </div>

                    <div class="dx-group" data-dx-section>
                        <div class="dx-head">
                            <span class="dx-label">Tindakan</span>
                            <button type="button" class="dx-btn-tambah" data-add>+ Tambah</button>
                        </div>
                        <div class="dx-list" data-list>
                            @foreach ($tindakanList as $nilai)
                                @include('pemeriksaan.diagnosa-row', ['name' => 'tindakan', 'label' => 'Tindakan', 'placeholder' => 'isi tindakan', 'selected' => $nilai, 'removable' => ! $loop->first])
                            @endforeach
                        </div>
                        <template data-template>
                            @include('pemeriksaan.diagnosa-row', ['name' => 'tindakan', 'label' => 'Tindakan', 'placeholder' => 'isi tindakan', 'removable' => true])
                        </template>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // "+ Tambah" menambah satu kotak isian; "×" menghapus kotak tambahan.
        (function () {
            var form = document.getElementById('form-diagnosa');
            form.addEventListener('click', function (e) {
                var section = e.target.closest('[data-dx-section]');
                if (!section) return;
                if (e.target.closest('[data-add]')) {
                    var tpl = section.querySelector('[data-template]');
                    section.querySelector('[data-list]').appendChild(tpl.content.cloneNode(true));
                    var selects = section.querySelectorAll('[data-list] input');
                    selects[selects.length - 1].focus();
                } else if (e.target.closest('[data-remove]')) {
                    e.target.closest('[data-row]').remove();
                }
            });
        })();
    </script>
</body>
</html>
