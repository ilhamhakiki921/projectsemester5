{{-- Satu kotak isian teks. Variabel: $name, $label, $placeholder, $selected (opsional, nilai terisi), $removable (opsional) --}}
<div class="repeat-row" data-row>
    <input
        type="text"
        name="{{ $name }}[]"
        aria-label="{{ $label }}"
        placeholder="{{ $placeholder }}"
        value="{{ $selected ?? '' }}"
    >
    @if ($removable ?? false)
        <button type="button" class="btn-hapus-row" data-remove aria-label="Hapus {{ strtolower($label) }}">&times;</button>
    @endif
</div>
