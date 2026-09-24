@extends('layouts.app')

@section('title', 'Login - SIP-REK')

@section('style')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        background: #ffffff;
    }

    .login-page {
        width: 100%;
        min-height: 100vh;

        display: flex;
        align-items: stretch;
        justify-content: center;
    }

    /* ================= LEFT PANEL (FOTO KLINIK) ================= */

    .login-left {
        position: relative;
        flex: 1 1 50%;
        overflow: hidden;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-left::before {
        content: '';
        position: absolute;
        inset: 0;

        background-image: url("{{ asset('images/background-login.jpeg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;

        filter: blur(2px);
        transform: scale(1.05);
    }

    .login-left::after {
        content: '';
        position: absolute;
        inset: 0;

        background: linear-gradient(
            180deg,
            rgba(150, 0, 0, 0.78) 0%,
            rgba(90, 0, 0, 0.82) 100%
        );
    }

    .login-left-logo {
        position: relative;
        z-index: 2;

        width: 300px;
        max-width: 60%;

        filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.25));
    }

    /* ================= RIGHT PANEL (FORM) ================= */

    .login-right {
        flex: 1 1 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #ffffff;
        padding: 40px 30px;
    }

    .login-form-wrapper {
        width: 100%;
        max-width: 380px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;

        margin-bottom: 30px;
    }

    .brand-icon {
        width: 48px;
        height: 48px;
        flex-shrink: 0;

        border-radius: 10px;

        background: linear-gradient(180deg, #c40000 0%, #7a0000 100%);
        box-shadow: 0 4px 10px rgba(140, 0, 0, 0.35);

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }

    .brand-icon img {
        width: 68%;
        height: 68%;
        object-fit: contain;
    }

    .brand-text h1 {
        font-size: 18px;
        font-weight: 700;
        color: #222;
        line-height: 1.2;
    }

    .brand-text p {
        font-size: 11.5px;
        color: #777;
        margin-top: 2px;
    }

    .form-heading {
        margin-bottom: 24px;
    }

    .form-heading h2 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .form-heading p {
        font-size: 12.5px;
        color: #888;
        margin-top: 4px;
    }

    .login-form {
        width: 100%;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;

        margin-bottom: 6px;

        font-size: 13px;
        font-weight: 600;

        color: #222;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control {
        width: 100%;
        height: 44px;

        padding: 0 42px 0 14px;

        border: 1px solid #ccc;
        border-radius: 8px;

        outline: none;

        font-family: 'Poppins', sans-serif;
        font-size: 13.5px;

        color: #333;

        transition: border-color 0.2s;
    }

    .form-control::placeholder {
        color: #aaa;
        letter-spacing: 0.5px;
    }

    .form-control:focus {
        border-color: #a00000;
    }

    .toggle-eye {
        position: absolute;

        right: 12px;
        top: 50%;
        transform: translateY(-50%);

        background: none;
        border: none;

        font-size: 15px;
        color: #888;
        cursor: pointer;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 4px;
    }

    .toggle-eye:hover {
        color: #a00000;
    }

    .login-options {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        margin-top: 2px;
        margin-bottom: 22px;
    }

    .forgot-password {
        font-size: 12px;
        font-weight: 600;

        color: #9d0000;
        text-decoration: none;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .btn-login {
        width: 100%;
        height: 46px;

        border: none;
        border-radius: 8px;

        background: linear-gradient(180deg, #c40000 0%, #8f0000 100%);
        color: white;

        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.5px;

        cursor: pointer;

        box-shadow: 0 4px 10px rgba(140, 0, 0, 0.4);

        transition: 0.2s;
    }

    .btn-login:hover {
        background: linear-gradient(180deg, #a00000 0%, #700000 100%);
    }

    /* ================= RESPONSIVE ================= */

    @media (max-width: 900px) {
        .login-left {
            display: none;
        }

        .login-right {
            flex: 1 1 100%;
        }
    }
</style>

@endsection


@section('content')

<div class="login-page">

    {{-- LEFT: FOTO KLINIK + LOGO --}}
    <div class="login-left">
        <img src="{{ asset('images/logo-sistem.png') }}" alt="Logo Klinik Merah Putih" class="login-left-logo">
    </div>

    {{-- RIGHT: FORM LOGIN --}}
    <div class="login-right">

        <div class="login-form-wrapper">

            {{-- BRAND / SISTEM --}}
            <div class="brand">
                <div class="brand-icon">
                    <img src="{{ asset('images/logo-sistem.png') }}" alt="Logo SIP-REK">
                </div>

                <div class="brand-text">
                    <h1>SIP-REK</h1>
                    <p>Sistem Informasi Pelayanan Rekam Medis</p>
                </div>
            </div>

            {{-- HEADING --}}
            <div class="form-heading">
                <h2>Masuk ke sistem</h2>
                <p>Gunakan akun yang diberikan administrator</p>
            </div>

            {{-- FORM --}}
            <form class="login-form" action="{{ route('login.process') }}" method="POST" autocomplete="off">

                @csrf

                {{-- USERNAME --}}
                <div class="form-group">

                    <label for="username">Username</label>

                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="username"
                            name="username"
                            class="form-control js-toggle-field"
                            placeholder="Masukkan username"
                            autocomplete="off"
                        >

                        <button type="button" class="toggle-eye js-toggle-eye" aria-label="Tampilkan username">
                            <i class="bi bi-eye-slash"></i>
                        </button>

                    </div>

                </div>


                {{-- PASSWORD --}}
                <div class="form-group">

                    <label for="password">Password</label>

                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control js-toggle-field"
                            placeholder="Masukkan password"
                            autocomplete="off"
                        >

                        <button type="button" class="toggle-eye js-toggle-eye" aria-label="Tampilkan password">
                            <i class="bi bi-eye-slash"></i>
                        </button>

                    </div>

                </div>


                {{-- OPTIONS --}}
                <div class="login-options">

                    <a href="{{ route('password.request') }}" class="forgot-password">
                        Lupa Password?
                    </a>

                </div>


                {{-- LOGIN BUTTON --}}
                <button type="submit" class="btn-login">
                    Masuk
                </button>

            </form>

        </div>

    </div>

</div>

<script>
    // Setiap ikon mata hanya mengatur kolomnya sendiri.
    // Klik mata di Username -> hanya Username yang terlihat/tersembunyi.
    // Klik mata di Password -> hanya Password yang terlihat/tersembunyi.
    (function () {
        var eyeButtons = document.querySelectorAll('.js-toggle-eye');

        eyeButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var field = btn.parentElement.querySelector('.js-toggle-field');
                var icon = btn.querySelector('i');

                var show = field.getAttribute('type') === 'password';

                field.setAttribute('type', show ? 'text' : 'password');

                icon.classList.toggle('bi-eye', show);
                icon.classList.toggle('bi-eye-slash', !show);
            });
        });
    })();
</script>

@endsection
