<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk') - SIP-REK</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-wrap">

        <div class="auth-hero">
            <img src="{{ asset('images/logo-putih.png') }}" alt="Logo SIP-REK">
        </div>

        <div class="auth-panel">
            <div class="auth-box">
                <div class="brand">
                    <img src="{{ asset('images/icon-sistem.png') }}" alt="SIP-REK">
                    <div>
                        <h2 class="brand-name">SIP-REK</h2>
                        <p class="brand-desc">Sistem Informasi Pelayanan Rekam Medis</p>
                    </div>
                </div>

                @yield('content')
            </div>
        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
