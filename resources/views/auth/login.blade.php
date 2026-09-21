@extends('layouts.app')

@section('content')
{{-- Backend contract unchanged: POST route('login') with email + password + @csrf,
     Laravel's @error blocks render validation feedback. Design-only rewrite. --}}
<div class="pl-login">
    <div class="pl-login__backdrop" aria-hidden="true">
        <img src="{{ asset('img/logo.png') }}" alt="" class="pl-login__watermark">
    </div>

    <div class="pl-card" role="main">
        {{-- Brand panel --}}
        <aside class="pl-brand">
            <div class="pl-brand__seals">
                <img src="{{ asset('img/logo.png') }}" alt="Philippine National Police">
                <img src="{{ asset('backend/img/logos/pasay-logo.png') }}" alt="Lungsod ng Pasay">
            </div>
            <p class="pl-brand__eyebrow">Pasay City Police Station</p>
            <h1 class="pl-brand__title">Pasay Police<br>Clearance</h1>
            <p class="pl-brand__lead">Clearance Management System for the issuance, verification and printing of police clearances.</p>
            <ul class="pl-brand__list">
                <li><i class="fas fa-user-check"></i><span>Applicant registration &amp; biometrics</span></li>
                <li><i class="fas fa-fingerprint"></i><span>Hit verification</span></li>
                <li><i class="fas fa-print"></i><span>Payments &amp; clearance printing</span></li>
            </ul>
            <p class="pl-brand__motto">Service &middot; Honor &middot; Justice</p>
        </aside>

        {{-- Form panel --}}
        <section class="pl-form">
            <div class="pl-form__head">
                <h2>Sign in</h2>
                <p>Use your system account to continue.</p>
            </div>

            @if ($errors->any())
                <div class="pl-alert" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="pl-form__body" novalidate>
                @csrf

                <div class="pl-field">
                    <label for="email">Email</label>
                    <div class="pl-input @error('email') is-invalid @enderror">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autocomplete="email" autofocus>
                    </div>
                </div>

                <div class="pl-field">
                    <label for="password">Password</label>
                    <div class="pl-input @error('password') is-invalid @enderror">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="pl-input__toggle" id="togglePassword" aria-label="Show password" title="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- <div class="form-1">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                        <p class="form-label forgot-password">Forgot password?</p>
                        </a>
                    @endif
                </div> --}}

                <button type="submit" class="btn btn-primary pl-submit">
                    <span>Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>

                {{-- <div class="form-1 register-btn">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">
                        <span>New with PNP Clearance System? Create Account</span>
                        </a>
                    @endif
                </div> --}}
            </form>

            <p class="pl-form__foot">&copy; {{ date('Y') }} Pasay City Police &middot; Philippine National Police</p>
        </section>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('docs/css/modern.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/login.css') }}?v={{ filemtime(public_path('backend/css/login.css')) }}" rel="stylesheet">
@endsection

@section('scripts')
<script>
    (function () {
        var btn = document.getElementById('togglePassword');
        var input = document.getElementById('password');
        if (!btn || !input) { return; }
        btn.addEventListener('click', function () {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
            btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            btn.title = show ? 'Hide password' : 'Show password';
            input.focus();
        });
    })();
</script>
@endsection
