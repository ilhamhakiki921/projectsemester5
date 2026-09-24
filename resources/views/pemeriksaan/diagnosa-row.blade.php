{{-- Satu baris isian. Variabel: $name, $label, $placeholder, $options, $selected (opsional), $no (opsional) --}}
<div class="dx-row" data-row>
    <span class="dx-no" data-no>{{ $no ?? '' }}</span>
    <select name="{{ $name }}[]" class="dx-select" aria-label="{{ $label }}" required>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $opt)
            <option value="{{ $opt->id }}" {{ ($selected ?? null) == $opt->id ? 'selected' : '' }}>{{ $opt->nama }}</option>
        @endforeach
    </select>
    <button type="button" class="dx-btn-hapus" data-remove aria-label="Hapus {{ strtolower($label) }}">Hapus</button>
</div>
