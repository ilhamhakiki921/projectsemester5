<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        // Nama obat TANPA dosis; dosis di kolom sendiri.
        $daftarObat = [
            ['nama_obat' => 'Paracetamol',        'dosis' => '500 mg', 'sediaan' => 'Tablet',        'stok' => 420, 'harga' => 500],
            ['nama_obat' => 'Amoxicillin',        'dosis' => '500 mg', 'sediaan' => 'Kapsul',        'stok' => 18,  'harga' => 1500],
            ['nama_obat' => 'Ibuprofen',          'dosis' => '400 mg', 'sediaan' => 'Tablet',        'stok' => 150, 'harga' => 800],
            ['nama_obat' => 'OBH Combi',          'dosis' => null,     'sediaan' => 'Sirup',         'stok' => 40,  'harga' => 18000],
            ['nama_obat' => 'Antasida Doen',      'dosis' => null,     'sediaan' => 'Tablet Kunyah', 'stok' => 200, 'harga' => 400],
            ['nama_obat' => 'Cetirizine',         'dosis' => '10 mg',  'sediaan' => 'Tablet',        'stok' => 90,  'harga' => 700],
            ['nama_obat' => 'Omeprazole',         'dosis' => '20 mg',  'sediaan' => 'Kapsul',        'stok' => 0,   'harga' => 2500],
            ['nama_obat' => 'Vitamin B Complex',  'dosis' => null,     'sediaan' => 'Tablet',        'stok' => 300, 'harga' => 300],
            ['nama_obat' => 'ORS (Oralit)',       'dosis' => null,     'sediaan' => 'Sachet',        'stok' => 60,  'harga' => 1500],
            ['nama_obat' => 'Salbutamol',         'dosis' => '100 mcg','sediaan' => 'Inhaler',       'stok' => 12,  'harga' => 45000],
        ];

        foreach ($daftarObat as $obat) {
            // firstOrCreate: menjalankan seeder ulang tidak menimpa stok/harga yang sudah diubah admin.
            Obat::firstOrCreate(
                ['nama_obat' => $obat['nama_obat'], 'dosis' => $obat['dosis'], 'sediaan' => $obat['sediaan']],
                ['stok' => $obat['stok'], 'harga' => $obat['harga']]
            );
        }
    }
}
