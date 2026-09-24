{{-- Satu kotak isian. Variabel: $name, $label, $placeholder, $options, $selected (opsional), $removable (opsional) --}}
<div class="dx-row" data-row>
    <select name="{{ $name }}[]" class="dx-select" aria-label="{{ $label }}" required>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $opt)
            <option value="{{ $opt->id }}" {{ ($selected ?? null) == $opt->id ? 'selected' : '' }}>{{ $opt->nama }}</option>
        @endforeach
    </select>
    @if ($removable ?? false)
        <button type="button" class="dx-btn-hapus" data-remove aria-label="Hapus {{ strtolower($label) }}">&times;</button>
    @endif
</div>
