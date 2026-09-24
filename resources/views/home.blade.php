@extends('layouts.auth')

@section('title', 'Beranda')

@section('content')
    <div class="home-box">
        <h1 class="auth-title">Selamat datang, {{ Auth::user()->name }}</h1>
        <p class="auth-sub">Anda berhasil masuk ke SIP-REK.</p>

        <form method="POST" action="{{ route('logout') }}">
            {{ csrf_field() }}
            <button type="submit" class="btn-masuk">Keluar</button>
        </form>
    </div>
@endsection
