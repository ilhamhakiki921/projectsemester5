// Autocomplete ICD-10 dan ICD-9 untuk form Diagnosa.
// Input bertanda data-icd="tindakan" -> isi "KODE - Nama tindakan" (ICD-9, sumber /icd9/cari)
// Input bertanda data-icd="utama"    -> isi kolom diagnosis + kode_icd10 (terpisah)
// Input bertanda data-icd="sekunder" -> isi "KODE - Judul" (dipakai juga di baris hasil "+ Tambah")
(function () {
    var form = document.getElementById('form-diagnosa');
    if (!form || !form.dataset.icdUrl) return;

    var url = form.dataset.icdUrl;
    var url9 = form.dataset.icd9Url || '';
    var box = document.createElement('div');
    box.className = 'icd-dropdown';
    box.hidden = true;
    document.body.appendChild(box);

    var active = null, items = [], index = -1, timer = null, controller = null;

    function esc(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function hide() { box.hidden = true; items = []; index = -1; }

    function position() {
        if (!active) return;
        var r = active.getBoundingClientRect();
        box.style.left = (r.left + window.scrollX) + 'px';
        box.style.top = (r.bottom + window.scrollY + 2) + 'px';
        box.style.width = r.width + 'px';
    }

    function render(list, pesan) {
        items = list;
        index = -1;

        if (!list.length) {
            box.innerHTML = '<div class="icd-empty">' +
                esc(pesan || (active && active.dataset.icd === 'tindakan'
                    ? 'Tidak ada hasil. Anda tetap bisa mengetik tindakan secara manual.'
                    : 'Tidak ada hasil. Anda tetap bisa mengetik diagnosis secara manual.')) + '</div>';
        } else {
            box.innerHTML = list.map(function (it, i) {
                return '<div class="icd-item" data-i="' + i + '">' +
                    '<span class="icd-code">' + esc(it.code) + '</span>' +
                    '<span class="icd-title">' + esc(it.title) + '</span></div>';
            }).join('');
        }

        position();
        box.hidden = false;
    }

    function highlight() {
        var nodes = box.querySelectorAll('.icd-item');
        nodes.forEach(function (n, i) { n.classList.toggle('is-active', i === index); });
        if (nodes[index]) nodes[index].scrollIntoView({ block: 'nearest' });
    }

    function choose(it) {
        if (!active || !it) return;

        if (active.dataset.icd === 'utama') {
            active.value = it.title;
            active.dataset.icdPicked = it.title;
            var kode = document.getElementById('kode_icd10');
            if (kode) kode.value = it.code;
        } else if (active.dataset.icd === 'tindakan') {
            // Tanda koma dibuang: daftar tindakan disimpan dipisah ", " sehingga koma di nama akan memecahnya
            active.value = it.code + ' - ' + String(it.title).replace(/,\s*/g, ' ');
        } else {
            active.value = it.code + ' - ' + it.title;
        }

        active.setCustomValidity('');
        hide();
    }

    function search(q) {
        if (controller) controller.abort();
        controller = new AbortController();

        fetch(url + '?q=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json' },
            signal: controller.signal
        })
            .then(function (r) { return r.ok ? r.json() : Promise.reject(); })
            .then(function (res) {
                if (active && active.value.trim() === q) render(res.data || [], res.pesan);
            })
            .catch(function (err) {
                if (!err || err.name !== 'AbortError') hide();
            });
    }

    form.addEventListener('input', function (e) {
        var input = e.target.closest('[data-icd]');
        if (!input) return;

        active = input;

        // Kalau teks diagnosis utama diubah setelah memilih, kode otomatis dikosongkan
        if (input.dataset.icd === 'utama' && input.dataset.icdPicked &&
            input.value !== input.dataset.icdPicked) {
            var kode = document.getElementById('kode_icd10');
            if (kode) kode.value = '';
            delete input.dataset.icdPicked;
        }

        clearTimeout(timer);
        var q = input.value.trim();
        if (q.length < 2) { hide(); return; }

        timer = setTimeout(function () { search(q); }, 300);
    });

    form.addEventListener('focusin', function (e) {
        var input = e.target.closest('[data-icd]');
        if (input) active = input;
    });

    document.addEventListener('keydown', function (e) {
        if (box.hidden) return;

        if (e.key === 'ArrowDown' && items.length) {
            e.preventDefault();
            index = (index + 1) % items.length;
            highlight();
        } else if (e.key === 'ArrowUp' && items.length) {
            e.preventDefault();
            index = (index - 1 + items.length) % items.length;
            highlight();
        } else if (e.key === 'Enter' && index >= 0) {
            e.preventDefault();   // jangan submit form saat memilih hasil
            choose(items[index]);
        } else if (e.key === 'Escape') {
            hide();
        }
    });

    // mousedown (bukan click) supaya input tidak kehilangan fokus sebelum dipilih
    box.addEventListener('mousedown', function (e) {
        var row = e.target.closest('.icd-item');
        if (!row) return;
        e.preventDefault();
        choose(items[Number(row.dataset.i)]);
    });

    document.addEventListener('mousedown', function (e) {
        if (!box.contains(e.target) && !e.target.closest('[data-icd]')) hide();
    });

    window.addEventListener('resize', position);
})();