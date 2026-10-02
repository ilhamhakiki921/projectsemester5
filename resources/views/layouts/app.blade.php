<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <title>@yield('title', 'Klinik Merah Putih')</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Warna fokus kolom isian: merah (fallback untuk kolom yang belum punya aturan sendiri) */
        :where(input:not([type="checkbox"]):not([type="radio"]):not([type="range"]):not([type="color"]):not([type="file"]):not([type="button"]):not([type="submit"]):not([type="reset"]), select, textarea):focus {
            outline: none;
            border-color: #b40000;
            box-shadow: 0 0 0 3px rgba(180, 0, 0, 0.12);
        }
    </style>

    @yield('style')
    
</head>

<body>

    @yield('content')

</body>
</html>