{{-- Satu kotak isian teks. Variabel: $name, $label, $placeholder, $selected (opsional, nilai terisi), $removable (opsional), $icd (opsional, true = aktifkan autocomplete ICD-10) --}}
<div class="repeat-row" data-row>
    <input
        type="text"
        name="{{ $name }}[]"
        @if ($icd ?? false) data-icd="{{ ($icd ?? '') === 'icd9' ? 'tindakan' : 'sekunder' }}" autocomplete="off" @endif
        aria-label="{{ $label }}"
        placeholder="{{ $placeholder }}"
        value="{{ $selected ?? '' }}"
    >
    @if ($removable ?? false)
        <button type="button" class="btn-hapus-row" data-remove aria-label="Hapus {{ strtolower($label) }}">&times;</button>
    @endif
</div>