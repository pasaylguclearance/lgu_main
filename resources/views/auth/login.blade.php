@extends('layouts.app')

@section('content')
<div class="login-page">
    <div class="login-card">
        <div class="login-brand">
            <img src="{{ asset('backend/img/companylogo.png') }}" alt="PNP Clearance System">
        </div>
        <h1 class="login-title">Welcome back</h1>
        <p class="login-subtitle">Sign in to continue to the PNP Clearance System</p>

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="login-field">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="login-field">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="login-remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="login-btn">Sign In</button>
        </form>
    </div>
    <p class="login-footer">&copy; {{ date('Y') }} Pasay City Police Station</p>
</div>
@endsection

@section('styles')
<style>
    html, body { height: 100%; margin: 0; }
    body { background: #f3f4f6; font-family: 'Nunito', sans-serif; }
    div#app, main.py-4 { height: 100%; padding: 0 !important; }

    .login-page {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        box-sizing: border-box;
    }
    .login-card {
        width: 100%;
        max-width: 400px;
        background: #091e3e;
        color: #fff;
        border-radius: 12px;
        padding: 36px 32px 32px;
        box-shadow: 0 20px 45px rgba(9, 30, 62, .25);
        box-sizing: border-box;
    }
    .login-brand { text-align: center; margin-bottom: 20px; }
    .login-brand img { max-width: 260px; width: 100%; height: auto; }

    .login-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        text-align: center;
    }
    .login-subtitle {
        margin: 6px 0 26px;
        font-size: 14px;
        text-align: center;
        color: rgba(255, 255, 255, .7);
    }

    .login-field {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .login-field label {
        flex: 0 0 96px;
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .85);
    }
    /* !important: customlogin.css (shared by the other auth pages) pins
       #email/#password to 250px with margin:auto, which misaligns them. */
    .login-field .form-control {
        flex: 1 1 0;
        width: auto !important;
        min-width: 0;
        margin: 0 !important;
        height: 44px;
        padding: 0 14px;
        border-radius: 6px !important;
        border: 1px solid rgba(255, 255, 255, .15);
        background: rgba(255, 255, 255, .08);
        color: #fff;
        font-size: 15px;
        box-shadow: none;
    }
    .login-field .form-control:focus {
        background: rgba(255, 255, 255, .12);
        border-color: #c6262f;
        box-shadow: 0 0 0 3px rgba(198, 38, 47, .3);
        color: #fff;
    }
    .login-field .form-control.is-invalid { border-color: #ffb100; background-image: none; }
    .login-field .invalid-feedback {
        display: block;
        flex: 0 0 100%;
        padding-left: 96px;   /* line up with the input, not the label */
        margin-top: 6px;
        font-size: 13px;
        color: #ffb100;
    }
    @media (max-width: 380px) {
        .login-field label { flex: 0 0 100%; margin-bottom: 6px; }
        .login-field .invalid-feedback { padding-left: 0; }
    }

    .login-remember {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
        font-size: 14px;
        color: rgba(255, 255, 255, .8);
    }
    .login-remember input { margin: 0; accent-color: #c6262f; }
    .login-remember label { margin: 0; cursor: pointer; }

    .login-btn {
        width: 100%;
        height: 46px;
        border: 0;
        border-radius: 6px;
        background: #c6262f;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .15s ease;
    }
    .login-btn:hover, .login-btn:focus { background: #a81f27; outline: none; }

    .login-footer {
        margin: 20px 0 0;
        font-size: 12px;
        color: #6b7280;
    }
</style>
@endsection
