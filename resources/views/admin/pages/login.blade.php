@extends('admin.layouts.auth')

@section('title', 'Admin Login')

@section('content')
    <main class="auth bg-auth">
        <div class="auth__box"><a class="ad-logo ad-logo--c" href="{{ url('admin/login') }}"><span
                    class="ad-logo__n">PATARA</span><span class="ad-logo__s">ADMIN PANEL</span></a>
            <h1>Welcome back</h1>

            <p>Sign in to manage your resort.</p>
            @if (session('status') === 'verification-link-verified')
                <div class="alert alert-success" role="status">
                    Your email has been verified. You can now sign in.
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form class="needs-validation" method="POST" action="{{ route('login.store') }}" novalidate>
                @csrf
                <label class="form-label" for="le">Email</label>
                <input class="form-control mb-3" id="le" type="email" name="email"
                    value="{{ old('email') }}" autocomplete="username" required autofocus>
                <label class="form-label" for="lp">Password</label>
                <input class="form-control mb-3" id="lp" type="password" name="password"
                    autocomplete="current-password" required>
                <div class="d-flex justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" id="rm">
                        <label class="form-check-label" for="rm">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}">Forgot
                        password?</a>
                </div>

                <button class="btn-ad w-100 justify-content-center" type="submit">Sign in</button>
                <p>
                    Don't have an account?
                    <a class="text-decoration-underline" href="{{ route('register') }}">Sign up</a>
                </p>
            </form>

            <div class="auth-divider" aria-hidden="true"><span>or</span></div>
            <a class="btn-google" href="{{ route('google.redirect') }}">
                <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.22 3.31v2.77h3.58c2.1-1.93 3.28-4.77 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.58-2.77c-.98.66-2.23 1.06-3.7 1.06-2.84 0-5.25-1.92-6.11-4.5H2.2v2.84A11 11 0 0012 23z" />
                    <path fill="#FBBC05" d="M5.89 14.13A6.6 6.6 0 015.54 12c0-.74.13-1.45.35-2.13V7.03H2.2A11 11 0 001 12c0 1.78.43 3.46 1.2 4.97l3.69-2.84z" />
                    <path fill="#EA4335" d="M12 4.58c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 1.19 14.97 0 12 0 7.73 0 4.02 2.45 2.2 6.03l3.69 2.84C6.75 6.5 9.16 4.58 12 4.58z" />
                </svg>
                <span>Continue with Google</span>
            </a>
        </div>
    </main>
@endsection
