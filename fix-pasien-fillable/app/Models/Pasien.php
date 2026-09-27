@"
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected `$table = 'pasien';

    protected `$fillable = [
        'no_rm',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'nik',
        'no_telepon',
        'alamat',
        'kecamatan',
    ];

    protected `$casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kunjungan()
    {
        return `$this->hasMany(Kunjungan::class);
    }
}
"@ | Set-Content -Encoding utf8 C:\xampp\htdocs\projectsemester5\app\Models\Pasien.php