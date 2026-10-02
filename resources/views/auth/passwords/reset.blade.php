@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 class="auth-title">Atur ulang password</h1>
    <p class="auth-sub">Masukkan email akun dan password baru Anda.</p>

    <form method="POST" action="{{ url('password/reset') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email</label>
            <div class="input-wrap">
                <input id="email" type="email" name="email" class="no-eye"
                       value="{{ $email or old('email') }}" required autofocus>
            </div>
            @if ($errors->has('email'))
                <p class="error-text">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">Password baru</label>
            <div class="input-wrap">
                <input id="password" type="password" name="password" autocomplete="new-password" required>
                @include('partials.eye', ['target' => 'password'])
            </div>
            @if ($errors->has('password'))
                <p class="error-text">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div class="field">
            <label for="password-confirm">Konfirmasi password baru</label>
            <div class="input-wrap">
                <input id="password-confirm" type="password" name="password_confirmation" autocomplete="new-password" required>
                @include('partials.eye', ['target' => 'password-confirm'])
            </div>
        </div>

        <button type="submit" class="btn-masuk" style="background: #16a34a;">Simpan Password</button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">&larr; Kembali ke halaman masuk</a>
    </div>
@endsection
