@extends('layouts.app')

@section('title', 'Login')

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
    align-items: center;
    justify-content: center;

    position: relative;

    background-image:
        linear-gradient(
            rgba(120, 0, 0, 0.42),
            rgba(120, 0, 0, 0.42)
        ),
        url("{{ asset('images/background-login.jpeg') }}");

    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

    .login-card {
        width: 380px;
        min-height: 460px;

        padding: 40px 35px 35px;

        background: #ffffff;
        border-radius: 12px;

        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.18);
    }

    .login-logo {
        width: 100px;
        height: 100px;

        margin: 0 auto 14px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }

    .login-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .clinic-name {
        text-align: center;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
        letter-spacing: 0.5px;

        color: #222;
    }

    .clinic-name span {
        display: block;
        color: #c40000;
    }

    .login-form {
        margin-top: 28px;
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

    .input-wrapper i {
        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        font-size: 14px;
        color: #555;
    }

    .form-control {
        width: 100%;
        height: 42px;

        padding: 0 14px 0 38px;

        border: 1px solid #aaa;
        border-radius: 6px;

        outline: none;

        font-family: 'Poppins', sans-serif;
        font-size: 13px;

        color: #333;
    }

    .form-control::placeholder {
        color: #999;
    }

    .form-control:focus {
        border-color: #a00000;
    }

    .login-options {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-top: 2px;
        margin-bottom: 22px;
    }

    .remember {
        display: flex;
        align-items: center;

        gap: 6px;

        font-size: 12px;
        color: #777;

        cursor: pointer;
    }

    .remember input {
        width: 15px;
        height: 15px;

        margin: 0;
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
        height: 44px;

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

    @media (max-width: 600px) {
        .login-card {
            width: 85%;
            max-width: 380px;
        }
    }
</style>

@endsection


@section('content')

<div class="login-page">

    <div class="login-card">

        {{-- LOGO --}}
        <div class="login-logo">
            <img src="{{ asset('images/logo-klinik.png') }}" alt="Logo Klinik">
        </div>


        {{-- NAMA KLINIK --}}
        <div class="clinic-name">
            KLINIK RAWAT INAP
            <span>MERAH PUTIH</span>
        </div>


        {{-- FORM LOGIN --}}
        <form class="login-form" action="{{ route('login.process') }}" method="POST">

            @csrf

            {{-- USERNAME --}}
            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <div class="input-wrapper">

                    <i class="bi bi-person-fill"></i>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                    >

                </div>

            </div>


            {{-- PASSWORD --}}
            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <div class="input-wrapper">

                    <i class="bi bi-lock-fill"></i>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                    >

                </div>

            </div>


            {{-- OPTIONS --}}
            <div class="login-options">

                <label class="remember">

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    Ingat saya

                </label>


                <a href="#" class="forgot-password">
                    Lupa Password?
                </a>

            </div>


            {{-- LOGIN BUTTON --}}
            <button
                type="submit"
                class="btn-login"
            >
                Login
            </button>

        </form>

    </div>

</div>

@endsection