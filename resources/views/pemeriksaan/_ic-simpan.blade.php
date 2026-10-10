{{--
    Penyimpanan & pemulihan isian form Informed Consent (dipakai Poli Umum dan Poli Gigi).
    Parameter: $poli, $rm, $icData (isian yang pernah disimpan, atau null)

    - Isian tersimpan otomatis (draft) sesaat setelah diketik / diubah, dan saat pindah tab.
    - Saat halaman dibuka lagi, semua isian dipulihkan (teks, pilihan, centang, tanda tangan).
    - window.icSimpanServer(true) dipanggil tombol Simpan setelah validasi lolos.
--}}
@push('scripts')
<script>
(function () {
    var URL_SIMPAN = @json(route('pemeriksaan.informed-consent.simpan', [$poli, $rm]));
    var TERSIMPAN  = @json($icData ?? null);

    function mulai() {
        var form = document.getElementById('formIc');
        if (! form) return;

        var tokenEl = document.querySelector('meta[name="csrf-token"]');
        var csrf = tokenEl ? tokenEl.content : '';
        var memuat = true, timer = null, kotor = false;

        // ---------- Ambil isian form: daftar pasangan [nama, nilai] ----------
        function entri() {
            var hasil = [];
            Array.prototype.forEach.call(form.elements, function (el) {
                var n = el.name, t = el.type;
                if (! n || n.charAt(0) === '_' || el.disabled) return;
                if (t === 'button' || t === 'submit' || t === 'reset' || t === 'file') return;
                if ((t === 'radio' || t === 'checkbox') && ! el.checked) return;
                hasil.push([n, el.value]);
            });
            return hasil;
        }

        // ---------- Gambar tanda tangan yang tersimpan kembali ke kanvas ----------
        function gambarTtd(hidden, dataUrl) {
            var box = hidden.parentNode, canvas = box && box.querySelector('canvas');
            if (! canvas) return;
            var img = new Image();
            img.onload = function () {
                var r = canvas.getBoundingClientRect();
                if (! r.width) return;
                canvas.getContext('2d').drawImage(img, 0, 0, r.width, r.height);
                box.classList.add('terisi');
            };
            img.src = dataUrl;
        }

        // ---------- Pulihkan isian ----------
        function pulihkan(daftar) {
            var peta = {};
            daftar.forEach(function (p) {
                (peta[p[0]] = peta[p[0]] || []).push(p[1] == null ? '' : String(p[1]));
            });

            // Poli Umum: keputusan dipilih lewat tombol (.icu-pilih), klik dulu supaya kotak penolakan ikut menyesuaikan
            var btn = peta['keputusan'] && form.querySelector('.icu-pilih[data-keputusan="' + peta['keputusan'][0] + '"]');
            if (btn) btn.click();

            var urut = {};
            Array.prototype.forEach.call(form.elements, function (el) {
                var n = el.name, t = el.type;
                if (! n || ! (n in peta)) return;
                if (t === 'button' || t === 'submit' || t === 'reset' || t === 'file') return;

                if (t === 'radio' || t === 'checkbox') {
                    var ada = peta[n].indexOf(el.value) !== -1;
                    if (el.checked !== ada) {
                        el.checked = ada;
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    return;
                }
                if (n === 'keputusan' && btn) return;     // sudah diatur lewat klik tombol di atas

                var i = urut[n] = (urut[n] || 0);
                urut[n]++;
                var v = peta[n][i];
                if (v === undefined || el.disabled) return;

                el.value = v;
                if (t === 'hidden') {
                    if (/^ttd_/.test(n) && v) gambarTtd(el, v);
                    return;
                }
                el.dispatchEvent(new Event(el.tagName === 'SELECT' ? 'change' : 'input', { bubbles: true }));
            });
        }

        if (TERSIMPAN && TERSIMPAN.length) pulihkan(TERSIMPAN);
        memuat = false;

        // ---------- Kirim ke server ----------
        function kirim(final) {
            clearTimeout(timer); timer = null;
            kotor = false;
            return fetch(URL_SIMPAN, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ data: entri(), lengkap: !! final })
            }).then(function (r) {
                if (! r.ok) throw new Error('gagal');
                return true;
            }).catch(function () {
                kotor = true;
                return false;
            });
        }
        window.icSimpanServer = kirim;

        // ---------- Simpan otomatis (draft) ----------
        function tandai() {
            if (memuat) return;
            kotor = true;
            clearTimeout(timer);
            timer = setTimeout(function () { timer = null; kirim(false); }, 800);
        }
        form.addEventListener('input', tandai);
        form.addEventListener('change', tandai);
        form.addEventListener('pointerup', tandai);                       // selesai menggambar tanda tangan
        form.addEventListener('click', function (e) {                      // tombol (Bersihkan, pilihan keputusan, dll)
            if (e.target.closest('button')) tandai();
        });

        // Pindah tab/halaman lewat link: simpan dulu, baru pindah
        document.addEventListener('click', function (e) {
            var a = e.target.closest('a[href]');
            if (! a || ! (kotor || timer)) return;
            if (a.target === '_blank' || e.ctrlKey || e.metaKey || e.shiftKey) return;
            var href = a.getAttribute('href');
            if (! href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return;
            e.preventDefault();
            kirim(false).then(function () { window.location.href = a.href; });
        }, true);

        // Tab ditutup / di-refresh saat ada perubahan yang belum terkirim (cadangan, batas ukuran ~64 KB)
        window.addEventListener('pagehide', function () {
            if (! (kotor || timer) || ! navigator.sendBeacon) return;
            var isi = JSON.stringify({ _token: csrf, data: entri(), lengkap: false });
            if (isi.length < 60000) navigator.sendBeacon(URL_SIMPAN, new Blob([isi], { type: 'application/json' }));
        });
    }

    // Jalankan setelah script halaman (kanvas tanda tangan, dll) selesai disiapkan
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mulai);
    else mulai();
})();
</script>
@endpush
