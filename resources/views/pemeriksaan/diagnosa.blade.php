{{--
    Tab "Diagnosa" (isi kartu saja; sidebar, header, banner pasien & tab memakai layout Asesmen Anda).
    Variabel dari controller:
      $action              URL simpan (opsional, default '#')
      $diagnosis           koleksi master diagnosis  (kolom: id, nama)
      $tindakan            koleksi master tindakan   (kolom: id, nama)
      $diagnosisUtama      id terpilih (opsional)
      $diagnosisSekunder   array id terpilih (opsional)
      $tindakanTerpilih    array id terpilih (opsional)
--}}
@php
    $diagnosis = $diagnosis ?? collect();
    $tindakan = $tindakan ?? collect();
    $utama = old('diagnosis_utama', $diagnosisUtama ?? null);
    $sekunder = old('diagnosis_sekunder', $diagnosisSekunder ?? []);
    $tindakanIds = old('tindakan', $tindakanTerpilih ?? []);
@endphp

<form action="{{ $action ?? '#' }}" method="POST" class="dx-card" id="form-diagnosa">
    @csrf
    <h2 class="dx-title">DIAGNOSIS</h2>

    {{-- 1. Diagnosis Utama --}}
    <div class="dx-group">
        <label class="dx-label" for="diagnosis_utama">Diagnosis Utama</label>
        <select id="diagnosis_utama" name="diagnosis_utama" class="dx-select" required>
            <option value="">Pilih diagnosis utama</option>
            @foreach ($diagnosis as $d)
                <option value="{{ $d->id }}" {{ $utama == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
            @endforeach
        </select>
    </div>

    {{-- 2. Diagnosis Sekunder --}}
    <div class="dx-group" data-dx-section>
        <div class="dx-head">
            <span class="dx-label">Diagnosis Sekunder</span>
            <button type="button" class="dx-btn-tambah" data-add>+ Tambah</button>
        </div>
        <div class="dx-empty" data-empty {{ count($sekunder) ? 'hidden' : '' }}>Klik Tambah untuk mengisi diagnosis sekunder.</div>
        <div class="dx-list" data-list>
            @foreach ($sekunder as $id)
                @include('pemeriksaan.diagnosa-row', ['name' => 'diagnosis_sekunder', 'label' => 'Diagnosis Sekunder', 'placeholder' => 'Pilih diagnosis sekunder', 'options' => $diagnosis, 'selected' => $id, 'no' => $loop->iteration])
            @endforeach
        </div>
        <template data-template>
            @include('pemeriksaan.diagnosa-row', ['name' => 'diagnosis_sekunder', 'label' => 'Diagnosis Sekunder', 'placeholder' => 'Pilih diagnosis sekunder', 'options' => $diagnosis])
        </template>
    </div>

    {{-- 3. Tindakan --}}
    <div class="dx-group" data-dx-section>
        <div class="dx-head">
            <span class="dx-label">Tindakan</span>
            <button type="button" class="dx-btn-tambah" data-add>+ Tambah</button>
        </div>
        <div class="dx-empty" data-empty {{ count($tindakanIds) ? 'hidden' : '' }}>Klik Tambah untuk mengisi tindakan.</div>
        <div class="dx-list" data-list>
            @foreach ($tindakanIds as $id)
                @include('pemeriksaan.diagnosa-row', ['name' => 'tindakan', 'label' => 'Tindakan', 'placeholder' => 'Pilih tindakan', 'options' => $tindakan, 'selected' => $id, 'no' => $loop->iteration])
            @endforeach
        </div>
        <template data-template>
            @include('pemeriksaan.diagnosa-row', ['name' => 'tindakan', 'label' => 'Tindakan', 'placeholder' => 'Pilih tindakan', 'options' => $tindakan])
        </template>
    </div>

    <div class="dx-actions">
        <button type="submit" class="dx-btn-simpan">Simpan</button>
    </div>
</form>
