@extends('layouts.app')

@section('title', 'Lupa Password - SIP-REK')

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

    .forgot-page {
        width: 100%;
        min-height: 100vh;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 20px;
    }

    .forgot-card {
        width: 100%;
        max-width: 400px;

        padding: 36px 32px;

        border-radius: 12px;
        border: 1px solid #eee;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .forgot-card h2 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a1a;

        margin-bottom: 6px;
    }

    .forgot-card p {
        font-size: 12.5px;
        color: #888;

        margin-bottom: 24px;
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

    .form-control {
        width: 100%;
        height: 44px;

        padding: 0 14px;

        border: 1px solid #ccc;
        border-radius: 8px;

        outline: none;

        font-family: 'Poppins', sans-serif;
        font-size: 13.5px;

        color: #333;
    }

    .form-control:focus {
        border-color: #a00000;
    }

    .btn-submit {
        width: 100%;
        height: 46px;

        border: none;
        border-radius: 8px;

        background: linear-gradient(180deg, #c40000 0%, #8f0000 100%);
        color: white;

        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;

        cursor: pointer;

        margin-top: 6px;
    }

    .back-login {
        display: block;

        margin-top: 18px;

        text-align: center;

        font-size: 12.5px;
        font-weight: 600;
        color: #9d0000;

        text-decoration: none;
    }

    .back-login:hover {
        text-decoration: underline;
    }
</style>

@endsection

@section('content')

<div class="forgot-page">

    <div class="forgot-card">

        <h2>Lupa Password</h2>
        <p>Masukkan username Anda, administrator akan membantu mereset password.</p>

        @if (session('status'))
            <div style="margin-bottom:16px;padding:10px 12px;border-radius:8px;background:#e8f7ee;color:#14532d;font-size:12.5px;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.request.store') }}">

            @csrf

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}">
                @error('username')
                    <small style="color:#d00000;font-size:12px;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Kirim Permintaan</button>

        </form>

        <a href="{{ route('login') }}" class="back-login">
            &larr; Kembali ke halaman login
        </a>

    </div>

</div>

@endsection
