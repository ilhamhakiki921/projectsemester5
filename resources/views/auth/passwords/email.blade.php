@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <h1 class="auth-title">Lupa password?</h1>
    <p class="auth-sub">Masukkan email akun Anda, kami kirimkan link untuk mengatur ulang password.</p>

    @if (session('status'))
        <p class="status-ok">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email</label>
            <div class="input-wrap">
                <input id="email" type="email" name="email" class="no-eye"
                       value="{{ old('email') }}" required autofocus>
            </div>
            @if ($errors->has('email'))
                <p class="error-text">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <button type="submit" class="btn-masuk">Kirim Link Reset</button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">&larr; Kembali ke halaman masuk</a>
    </div>
@endsection
