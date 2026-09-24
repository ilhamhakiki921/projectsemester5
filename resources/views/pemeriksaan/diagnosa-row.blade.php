{{-- Satu kotak isian teks. Variabel: $name, $label, $placeholder, $selected (opsional, nilai terisi), $removable (opsional) --}}
<div class="dx-row" data-row>
    <input
        type="text"
        name="{{ $name }}[]"
        class="dx-select"
        aria-label="{{ $label }}"
        placeholder="{{ $placeholder }}"
        value="{{ $selected ?? '' }}"
    >
    @if ($removable ?? false)
        <button type="button" class="dx-btn-hapus" data-remove aria-label="Hapus {{ strtolower($label) }}">&times;</button>
    @endif
</div>
