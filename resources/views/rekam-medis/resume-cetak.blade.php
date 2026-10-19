<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Resume Medis — {{ $resume['nama'] }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/resume-medis.css') }}">
<style>
    body { margin: 0; background: #eee; }
    .halaman { width: 210mm; min-height: 297mm; margin: 12px auto; background: #fff; padding: 14mm 14mm 12mm; }
    .bar { position: fixed; top: 10px; right: 14px; z-index: 10; }
    .bar button { border: 0; border-radius: 6px; padding: 8px 16px; margin-left: 8px; font: 600 12px sans-serif; cursor: pointer; color: #fff; background: #a30000; }
    .bar button.x { background: #666; }
    @page { size: A4; margin: 12mm; }
    @media print {
        body { background: #fff; }
        .halaman { margin: 0; width: auto; min-height: 0; padding: 0; }
        .bar { display: none; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="bar">
    <button onclick="window.print()">Cetak</button>
</div>

<div class="halaman">
    @include('rekam-medis._resume-medis', ['resume' => $resume])
</div>
<script>
// Dialog cetak langsung terbuka begitu lembar selesai dimuat (tombol "Cetak" di pojok tetap ada untuk cetak ulang)
window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 600); });
</script>
</body>
</html>
