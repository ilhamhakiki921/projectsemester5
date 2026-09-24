// Tombol "+ Tambah" menambah satu baris; "Hapus" menghapusnya. Tanpa library.
(function () {
    var form = document.getElementById('form-diagnosa');
    if (!form) return;

    function refresh(section) {
        var rows = section.querySelectorAll('[data-row]');
        rows.forEach(function (row, i) {
            row.querySelector('[data-no]').textContent = i + 1;
        });
        section.querySelector('[data-empty]').hidden = rows.length > 0;
    }

    form.addEventListener('click', function (e) {
        var section = e.target.closest('[data-dx-section]');
        if (!section) return;

        if (e.target.closest('[data-add]')) {
            var tpl = section.querySelector('[data-template]');
            section.querySelector('[data-list]').appendChild(tpl.content.cloneNode(true));
            refresh(section);
            var selects = section.querySelectorAll('[data-list] select');
            selects[selects.length - 1].focus();
        } else if (e.target.closest('[data-remove]')) {
            e.target.closest('[data-row]').remove();
            refresh(section);
        }
    });
})();
