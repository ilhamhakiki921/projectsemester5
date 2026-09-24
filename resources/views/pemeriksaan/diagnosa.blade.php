{{--
    Tab "Diagnosa" (isi kartu saja; sidebar, header, banner pasien & tab memakai layout Anda).
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
    if (count($sekunder) === 0) { $sekunder = [null]; }
    if (count($tindakanIds) === 0) { $tindakanIds = [null]; }
@endphp

<form action="{{ $action ?? '#' }}" method="POST" class="dx-card" id="form-diagnosa">
    @csrf
    <h2 class="dx-title">DIAGNOSIS</h2>

    {{-- Kotak 1: Diagnosis Utama --}}
    <div class="dx-group">
        <label class="dx-label" for="diagnosis_utama">Diagnosis Utama</label>
        <select id="diagnosis_utama" name="diagnosis_utama" class="dx-select" required>
            <option value="">isi diagnosis utama</option>
            @foreach ($diagnosis as $d)
                <option value="{{ $d->id }}" {{ $utama == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="dx-grid">
        {{-- Kotak 2: Diagnosis Sekunder --}}
        <div class="dx-group" data-dx-section>
            <div class="dx-head">
                <span class="dx-label">Diagnosis Sekunder</span>
                <button type="button" class="dx-btn-tambah" data-add>+ Tambah</button>
            </div>
            <div class="dx-list" data-list>
                @foreach ($sekunder as $id)
                    @include('pemeriksaan.diagnosa-row', ['name' => 'diagnosis_sekunder', 'label' => 'Diagnosis Sekunder', 'placeholder' => 'isi diagnosis sekunder', 'options' => $diagnosis, 'selected' => $id, 'removable' => ! $loop->first])
                @endforeach
            </div>
            <template data-template>
                @include('pemeriksaan.diagnosa-row', ['name' => 'diagnosis_sekunder', 'label' => 'Diagnosis Sekunder', 'placeholder' => 'isi diagnosis sekunder', 'options' => $diagnosis, 'removable' => true])
            </template>
        </div>

        {{-- Kotak 3: Tindakan --}}
        <div class="dx-group" data-dx-section>
            <div class="dx-head">
                <span class="dx-label">Tindakan</span>
                <button type="button" class="dx-btn-tambah" data-add>+ Tambah</button>
            </div>
            <div class="dx-list" data-list>
                @foreach ($tindakanIds as $id)
                    @include('pemeriksaan.diagnosa-row', ['name' => 'tindakan', 'label' => 'Tindakan', 'placeholder' => 'isi tindakan', 'options' => $tindakan, 'selected' => $id, 'removable' => ! $loop->first])
                @endforeach
            </div>
            <template data-template>
                @include('pemeriksaan.diagnosa-row', ['name' => 'tindakan', 'label' => 'Tindakan', 'placeholder' => 'isi tindakan', 'options' => $tindakan, 'removable' => true])
            </template>
        </div>
    </div>
</form>
