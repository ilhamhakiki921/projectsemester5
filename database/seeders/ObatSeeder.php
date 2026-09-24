<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $daftarObat = [
            ['nama_obat' => 'Paracetamol 500mg', 'sediaan' => 'Tablet'],
            ['nama_obat' => 'Amoxicillin 500mg', 'sediaan' => 'Kapsul'],
            ['nama_obat' => 'Ibuprofen 400mg', 'sediaan' => 'Tablet'],
            ['nama_obat' => 'OBH Combi', 'sediaan' => 'Sirup'],
            ['nama_obat' => 'Antasida Doen', 'sediaan' => 'Tablet Kunyah'],
            ['nama_obat' => 'Cetirizine 10mg', 'sediaan' => 'Tablet'],
            ['nama_obat' => 'Omeprazole 20mg', 'sediaan' => 'Kapsul'],
            ['nama_obat' => 'Vitamin B Complex', 'sediaan' => 'Tablet'],
            ['nama_obat' => 'ORS (Oralit)', 'sediaan' => 'Sachet'],
            ['nama_obat' => 'Salbutamol Inhaler', 'sediaan' => 'Inhaler'],
        ];

        foreach ($daftarObat as $obat) {
            Obat::updateOrCreate(['nama_obat' => $obat['nama_obat']], $obat);
        }
    }
}
