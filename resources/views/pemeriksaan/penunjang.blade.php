<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Penunjang - Klinik Merah Putih</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
@php
    // ---- Data dari controller (semua opsional, ada nilai bawaan) ----
    $pasien   = $pasien ?? null;
    $base     = $baseUrl ?? url('/pemeriksaan/umum/' . ($no_rm ?? '0'));   // /pemeriksaan/umum/{no_rm}
    $storeUrl = $storeUrl ?? '';                                            // POST simpan ke Rekam Medis
    $riwayat  = $riwayat ?? [];                                             // [{id, items:[], tanggal, status}]

    $tabs = ['Assesment' => 'asesmen', 'SOAP' => 'soap', 'Diagnosa' => 'diagnosa', 'Resep' => 'resep',
             'Clinical Phatway' => 'clinical-pathway', 'Penunjang' => 'penunjang', 'Rujukan' => 'rujukan', 'autentikasi' => 'autentikasi'];

    $kolom = [
      [
        ['Kimia Klinik', [
          ['Kadar Gula Darah', ['Glukosa darah puasa','Glukosa darah 2 jam pp','Glukosa darah sewaktu','HbA1c']],
          ['Profil Lemak', ['Cholesterol total','HDL - Cholesterol','LDL - Cholesterol','Trigliserida']],
          ['Faal Hati', ['Bilirubin (Direk/Total)','Alkali Phosphatase','Gamma GT','SGOT','SGPT','Total Protein','Albumin','Globulin']],
          ['Faal Ginjal', ['Serum Creatinin','BUN','Uric Acid']],
          ['Penanda Tumor', ['CEA','Ca 125','Ca 15-3','AFP','Beta HCG','Ca 19-9']],
        ]],
      ],
      [
        ['Elektrolit', [[null, ['Natrium','Kalium','Chlorida','Calcium']]]],
        ['Hematologi', [[null, ['Darah Lengkap','LED / BBS','Hb','Eritrosit','Leukosit','Hitung Jenis','Hapusan darah tepi','Trombosit','Hematokrit / PCV','Malaria','Golongan darah + Rhesus','Waktu pendarahan','Waktu pembekuan']]]],
        ['Faeces', [[null, ['Faeces lengkap']]]],
        ['Tiroid', [[null, ['T3','T4','TSHs','FT4']]]],
        ['TORCH', [[null, ['Toxoplasma','Rubella','Cyto Megalo Virus (CMV)','Herpes Simplex']]]],
      ],
      [
        ['Urine', [[null, ['Urine lengkap','Albumin','Reduksi','Urobilin','Bilirubin','Keton','Sedimen','pH','Berat Jenis','Nitrit','Test Kehamilan','Plano Test','Narkotika','Amphetamine']]]],
        ['Immunologi', [[null, ['Widal','VDRL / TPHA','HIV','HBsAg','HBsAb','HBeAg','HBeAb','Anti HCV','Anti Hbs','ASTO','Helicobacter Pylori']]]],
        ['Lain - lain', [[null, ['Analisa Sperma','Pemeriksaan ECG']]]],
      ],
    ];
@endphp
<style>
  :root{--red:#a11a1e;--red-d:#5c0a0c;--red-g:linear-gradient(90deg,#a11a1e,#5c0a0c);--line:#cfcfcf;--ink:#1d1d1d}
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Poppins',sans-serif;color:var(--ink);background:#f3f3f3;font-size:13px;display:flex;min-height:100vh}
  a{color:inherit;text-decoration:none}

  /* Sidebar */
  .side{width:220px;flex-shrink:0;background:linear-gradient(180deg,#8c1216,#4a0708);color:#fff;padding:18px 16px;display:flex;flex-direction:column;position:sticky;top:0;height:100vh}
  .brand{display:flex;align-items:center;gap:10px;font-size:11px;padding-bottom:18px;border-bottom:1px solid rgba(255,255,255,.25)}
  .brand i{width:38px;height:38px;border-radius:9px;background:#fff1;display:grid;place-items:center;font-style:normal;font-size:20px}
  .menu{margin-top:30px;display:flex;flex-direction:column;gap:6px}
  .menu a{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:8px;font-size:12px;font-weight:500}
  .menu a.on{background:#c01f24;box-shadow:0 2px 6px #0004}
  .menu a.sub{padding-left:50px}
  .menu a:hover:not(.on){background:#ffffff14}
  .menu svg{width:16px;height:16px;fill:none;stroke:#fff;stroke-width:2}
  .logout{margin-top:auto;padding-top:14px;border-top:1px solid rgba(255,255,255,.25)}

  /* Main */
  main{flex:1;min-width:0;background:#fff}
  .top{display:flex;justify-content:space-between;align-items:center;padding:16px 22px;color:var(--red);font-weight:600;font-size:15px}
  .top span{display:flex;align-items:center;gap:8px}
  .top svg{width:22px;height:22px;fill:var(--red)}
  .wrap{padding:0 22px 40px}
  .back{font-size:12px;font-weight:500;display:inline-block;margin-bottom:10px}
  .ident{background:var(--red-g);border-radius:12px;color:#fff;padding:14px 22px;display:flex;align-items:center;gap:22px;flex-wrap:wrap}
  .ident h2{font-size:16px;font-weight:500;min-width:150px}
  .ident h2 small{display:block;font-size:14px;font-weight:500}
  .ident label{flex:1;min-width:110px;font-size:10px;font-weight:600;text-align:center}
  .ident input{display:block;width:100%;height:20px;margin-top:5px;border:0;border-radius:5px;padding:0 8px;font:inherit;font-size:11px}

  .tabs{display:flex;justify-content:space-between;align-items:center;padding:16px 8px 10px;font-size:11px;font-weight:500;overflow-x:auto;gap:10px}
  .tabs a{padding:3px 12px;border-radius:14px;white-space:nowrap}
  .tabs a.on{background:var(--red-g);color:#fff;padding:3px 26px}

  .card{border:1px solid #777;border-radius:12px;padding:22px 20px 26px}
  .card h3{font-size:14px;font-weight:600}
  .card .sub{font-size:9.5px;margin-top:2px}
  .rt{width:100%;border-collapse:separate;border-spacing:0;margin-top:14px}
  .rt th{background:var(--red-g);color:#fff;font-size:12px;font-weight:600;padding:9px 14px;text-align:center}
  .rt th:first-child{border-radius:6px 0 0 6px}.rt th:last-child{border-radius:0 6px 6px 0}
  .rt td{padding:10px 14px;border-bottom:1px solid #eee;text-align:center;font-size:12px;vertical-align:top}
  .rt td.l{text-align:left}
  .empty td{text-align:center;padding:36px;font-size:11px;font-weight:500;border:0}
  .badge{display:inline-block;background:#fff3cd;color:#8a6100;border-radius:10px;padding:2px 10px;font-size:11px;font-weight:500}
  .del{border:1px solid var(--red);color:var(--red);background:#fff;border-radius:6px;padding:2px 10px;font:inherit;font-size:11px;cursor:pointer}
  .del:hover{background:var(--red);color:#fff}

  .ftitle{font-size:13.5px;font-weight:600;margin:44px 0 12px 8px}
  .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;align-items:start}
  .col{display:flex;flex-direction:column;gap:14px}
  .box{border:1px solid var(--line);border-radius:8px;box-shadow:0 2px 5px #0002;overflow:hidden;background:#fff}
  .box>h4{background:var(--red-g);color:#fff;text-align:center;font-size:12px;font-weight:600;padding:9px;margin:0;border-radius:8px}
  .box .in{padding:10px 14px 12px}
  .grp{font-size:10px;font-weight:600;margin:8px 0 5px}
  .ck{display:flex;align-items:center;gap:10px;font-size:10px;font-weight:500;padding:4px 0;cursor:pointer}
  .ck input{width:14px;height:14px;accent-color:var(--red);flex-shrink:0;cursor:pointer}

  .opt{margin-top:26px;background:#d4d4d4;border:1px solid var(--line);border-radius:8px;padding:9px 16px;display:flex;flex-wrap:wrap;gap:22px;font-size:10px;font-weight:500}
  .opt label{display:flex;align-items:center;gap:8px;cursor:pointer}
  .opt input{accent-color:var(--red)}
  .ket{margin-top:16px;font-size:10px;font-weight:600}
  .ket textarea{display:block;width:100%;height:52px;margin-top:5px;border:1px solid var(--line);border-radius:6px;padding:7px 10px;font:inherit;font-size:10px;resize:vertical}
  .act{margin-top:26px;display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap}
  .btn{border:0;border-radius:6px;padding:8px 14px;font:inherit;font-size:11px;font-weight:600;cursor:pointer}
  .btn.go{background:#12c400;color:#fff}
  .btn.next{background:var(--red-g);color:#fff;padding:8px 22px}
  .btn:disabled{opacity:.6;cursor:wait}

  .toast{position:fixed;top:18px;right:18px;background:#1d1d1d;color:#fff;font-size:12px;padding:10px 16px;border-radius:8px;border-left:4px solid #12c400;box-shadow:0 4px 14px #0004;opacity:0;transform:translateY(-8px);transition:.25s;pointer-events:none;z-index:9}
  .toast.err{border-color:#ff5a5f}.toast.show{opacity:1;transform:none}

  @media(max-width:1000px){.grid{grid-template-columns:1fr 1fr}}
  @media(max-width:760px){.side{display:none}.grid{grid-template-columns:1fr}}
</style>
</head>
<body>

<aside class="side">
  <div class="brand"><i>♥</i> Klinik Merah Putih</div>
  <nav class="menu">
    <a href="{{ url('/dashboard') }}"><svg viewBox="0 0 24 24"><path d="M3 11l9-8 9 8M5 10v10h5v-6h4v6h5V10"/></svg>Dashboard</a>
    <a href="{{ url('/pendaftaran') }}"><svg viewBox="0 0 24 24"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 11h6M9 15h6"/></svg>Pendaftaran</a>
    <a href="{{ url('/pemeriksaan') }}" class="on"><svg viewBox="0 0 24 24"><path d="M12 20s-7-4.5-7-10a4 4 0 017-2.5A4 4 0 0119 10c0 5.500-7 10-7 10z"/></svg>Pemeriksaan</a>
    <a href="{{ url('/penunjang') }}" class="sub">Penunjang</a>
    <a href="{{ url('/farmasi') }}" class="sub">Farmasi</a>
    <a href="{{ url('/rekam-medis') }}" class="sub">Rekam Medis</a>
    <a href="{{ url('/laporan') }}" class="sub">Laporan</a>
  </nav>
  <a href="{{ url('/logout') }}" class="logout" style="padding-left:14px;font-size:12px;font-weight:500">Keluar</a>
</aside>

<main>
  <div class="top">
    <span><svg viewBox="0 0 24 24"><path d="M12 3L2 12h3v8h5v-6h4v6h5v-8h3z"/></svg>Penunjang</span>
    <span><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="11"/><circle cx="12" cy="9" r="3.500" fill="#fff"/><path d="M5.500 19c1-4 4-5 6.500-5s5.500 1 6.500 5z" fill="#fff"/></svg><span style="color:var(--red)">Admin</span></span>
  </div>

  <div class="wrap">
    <a class="back" href="{{ url('/pemeriksaan') }}">⟵ Kembali ke Antrian</a>

    <section class="ident">
      <h2>Identitas Pasien<small style="margin-top:8px;font-size:17px;font-weight:600;text-transform:uppercase">{{ $pasien->nama ?? 'Budi Santoso' }}</small></h2>
      <label>No.RM<input readonly value="{{ $pasien->no_rm ?? ($no_rm ?? '') }}"></label>
      <label>NIK<input readonly value="{{ $pasien->nik ?? '' }}"></label>
      <label>Jenis Kelamin<input readonly value="{{ $pasien->jenis_kelamin ?? '' }}"></label>
      <label>Tanggal Lahir<input readonly value="{{ $pasien->tanggal_lahir ?? '' }}"></label>
      <label>Usia<input readonly value="{{ $pasien->usia ?? '' }}"></label>
    </section>

    <nav class="tabs">
      @foreach($tabs as $label => $slug)
        <a href="{{ $base . '/' . $slug }}" class="{{ $slug === 'penunjang' ? 'on' : '' }}">{{ $label }}</a>
      @endforeach
    </nav>

    <section class="card">
      <h3>Pemeriksaan Penunjang — Permintaan Laboratorium</h3>
      <p class="sub">Formulir mengikuti form Instalasi Laboratorium Merah Putih Group</p>

      <table class="rt">
        <thead><tr><th style="width:60px">No</th><th>Pemeriksaan Diminta</th><th style="width:150px">Tanggal</th><th style="width:120px">Status</th><th style="width:110px">Action</th></tr></thead>
        <tbody id="rows"></tbody>
      </table>

      <h3 class="ftitle">Formulir Permintaan Pemeriksaan Laboratorium</h3>
      <form id="form" autocomplete="off">
        <div class="grid">
          @foreach($kolom as $kotakKotak)
            <div class="col">
              @foreach($kotakKotak as [$judul, $grupGrup])
                <div class="box">
                  <h4>{{ $judul }}</h4>
                  <div class="in">
                    @foreach($grupGrup as [$sub, $items])
                      @if($sub)<div class="grp">{{ $sub }}</div>@endif
                      @foreach($items as $item)
                        <label class="ck"><input type="checkbox" name="pemeriksaan[]" value="{{ $item }}">{{ $item }}</label>
                      @endforeach
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          @endforeach
        </div>

        <div class="opt">
          <label><input type="checkbox" name="perlu_puasa" value="1">Pemeriksaan perlu puasa</label>
          <label><input type="radio" name="hasil_ke" value="dokter" checked>Hasil dikirim ke Dokter</label>
          <label><input type="radio" name="hasil_ke" value="pasien">Hasil diserahkan ke Pasien</label>
        </div>

        <div class="ket">Keterangan Klinik
          <textarea name="keterangan" placeholder="Catatan klinis untuk laboratorium (opsional)"></textarea>
        </div>

        <div class="act">
          <button type="submit" class="btn go" id="btnBuat">+ Buat Permintaan Laboratorium</button>
          <button type="button" class="btn next" id="btnLanjut">Lanjut ke Rujukan</button>
        </div>
      </form>
    </section>
  </div>
</main>

<div class="toast" id="toast"></div>

<script>
(() => {
  const storeUrl = @json($storeUrl);              // kosong = mode demo (tanpa simpan ke server)
  const nextUrl  = @json($base . '/rujukan');
  const csrf     = document.querySelector('meta[name="csrf-token"]').content;
  let data       = @json($riwayat);

  const rows = document.getElementById('rows'), form = document.getElementById('form'),
        toastEl = document.getElementById('toast'), btn = document.getElementById('btnBuat');
  const esc = s => String(s).replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));

  function toast(msg, err) {
    toastEl.textContent = msg;
    toastEl.className = 'toast show' + (err ? ' err' : '');
    clearTimeout(toast.t); toast.t = setTimeout(() => toastEl.classList.remove('show'), 2400);
  }

  function render() {
    if (!data.length) {
      rows.innerHTML = '<tr class="empty"><td colspan="5">Belum ada permintaan penunjang.</td></tr>';
      return;
    }
    rows.innerHTML = data.map((r, i) => `
      <tr>
        <td>${i + 1}</td>
        <td class="l">${esc(r.items.join(', '))}</td>
        <td>${esc(r.tanggal)}</td>
        <td><span class="badge">${esc(r.status || 'Menunggu')}</span></td>
        <td><button type="button" class="del" data-i="${i}">Hapus</button></td>
      </tr>`).join('');
  }

  rows.addEventListener('click', async e => {
    const b = e.target.closest('.del'); if (!b) return;
    const r = data[+b.dataset.i];
    try {
      if (r.hapus_url) {
        const res = await fetch(r.hapus_url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
        if (!res.ok) throw new Error();
      }
      data.splice(+b.dataset.i, 1); render(); toast('Permintaan dihapus');
    } catch (_) { toast('Gagal menghapus', true); }
  });

  form.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(form);
    const items = fd.getAll('pemeriksaan[]');
    if (!items.length) return toast('Pilih minimal satu pemeriksaan', true);

    btn.disabled = true;
    try {
      let saved = { items, tanggal: new Date().toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}), status: 'Menunggu' };
      if (storeUrl) {
        const res = await fetch(storeUrl, { method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
        if (!res.ok) throw new Error();
        saved = { ...saved, ...(await res.json()) };
      }
      data.push(saved); render(); form.reset();
      toast('Permintaan tersimpan ke Rekam Medis');
    } catch (_) {
      toast('Gagal menyimpan, coba lagi', true);
    } finally { btn.disabled = false; }
  });

  document.getElementById('btnLanjut').addEventListener('click', () => {
    if (form.querySelector('input[name="pemeriksaan[]"]:checked'))
      return toast('Ada pemeriksaan terpilih, klik "Buat Permintaan" dulu', true);
    location.href = nextUrl;
  });

  render();
})();
</script>
</body>
</html>
