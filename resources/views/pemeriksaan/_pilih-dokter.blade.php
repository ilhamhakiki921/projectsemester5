{{--
    Dropdown pilihan dokter (data dari config/dokter.php).
    Parameter: $id, $name, $class
    Saat form dibuka, dropdown menampilkan hint "Pilih dokter" (belum ada dokter terpilih).
    Pilihan hanya dipertahankan kalau form dikembalikan karena error validasi (old input).
--}}
@php
    $daftarDokter = config('dokter.daftar', []);
    $terpilih     = old($name, '');
@endphp
<select class="{{ $class }}" id="{{ $id }}" name="{{ $name }}">
    <option value="" @selected($terpilih === '')>Pilih dokter</option>
    @foreach ($daftarDokter as $slugPoli => $namaDokter)
        <optgroup label="Poli {{ ucwords(str_replace('-', ' ', $slugPoli)) }}">
            @foreach ($namaDokter as $nm)
                <option value="{{ $nm }}" @selected($nm === $terpilih)>{{ $nm }}</option>
            @endforeach
        </optgroup>
    @endforeach
</select>
