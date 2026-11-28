@extends('layouts.app')

@section('title', 'Two-factor authentication')
@section('body_class', 'page-two-factor-settings')

@push('styles')
<link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<section class="hero hero--auth bg-bed1 tfa-hero">
  <div class="container hero__in">
    <p class="hero__eyebrow">Account security</p>
    <h1 class="hero__title">Two-factor authentication</h1>
  </div>
</section>

<main id="main" class="sec--tint tfa-sec">
  <div class="container">
    @php($user = auth()->user())

    <section class="tfa-card" aria-labelledby="tfa-heading">
      <header class="tfa-card__head">
        <span class="tfa-eyebrow">Protect your account</span>
        <h2 id="tfa-heading">A more secure sign-in</h2>
        <p>Use an authenticator app to add a second step whenever you sign in.</p>
      </header>

      @if (session('status'))
        <div class="tfa-message tfa-message--success" role="status">
          {{ ucwords(str_replace(['-', '_'], ' ', session('status'))) }}
        </div>
      @endif

      @if ($errors->any())
        <div class="tfa-message tfa-message--error" role="alert">{{ $errors->first() }}</div>
      @endif

      @if (!$user->two_factor_secret)
        <div class="tfa-state">
          <span class="tfa-state__mark" aria-hidden="true">1</span>
          <div>
            <h3>Two-factor authentication is off</h3>
            <p>Enable it to require a code from your authenticator app in addition to your password.</p>
            <form method="POST" action="{{ route('two-factor.enable') }}">
              @csrf
              <button class="btn-pt" type="submit">Set up two-factor authentication</button>
            </form>
          </div>
        </div>
      @elseif (!$user->two_factor_confirmed_at)
        <div class="tfa-state tfa-state--pending">
          <span class="tfa-state__mark" aria-hidden="true">2</span>
          <div>
            <h3>Finish setting up your authenticator</h3>
            <p>Scan this QR code with Google Authenticator or another TOTP app, then enter the current 6-digit code.</p>
          </div>
        </div>

        <div class="tfa-setup">
          <div class="tfa-qr" aria-label="Authenticator setup QR code">
            {!! $user->twoFactorQrCodeSvg() !!}
          </div>

          <form class="tfa-code-form" method="POST" action="{{ route('two-factor.confirm') }}">
            @csrf
            <label for="tfa-code">Authenticator code</label>
            <input
              id="tfa-code"
              type="text"
              name="code"
              inputmode="numeric"
              autocomplete="one-time-code"
              pattern="[0-9]{6}"
              maxlength="6"
              placeholder="000000"
              required
              autofocus
            >
            <p>Use a fresh code from the app. The code used here cannot be reused at sign-in.</p>
            <button class="btn-pt" type="submit">Confirm and enable</button>
          </form>
        </div>
      @else
        <div class="tfa-state tfa-state--enabled">
          <span class="tfa-state__mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="m5 12 4 4L19 6"></path>
            </svg>
          </span>
          <div>
            <h3>Two-factor authentication is on</h3>
            <p>Your account asks for an authenticator code after your password.</p>
          </div>
        </div>

        <div class="tfa-recovery">
          <div>
            <h3>Recovery codes</h3>
            <p>Keep these codes somewhere safe. Each code can be used once if you lose access to your authenticator.</p>
          </div>
          <div class="tfa-recovery__codes">
            @foreach ($user->recoveryCodes() as $code)
              <code>{{ $code }}</code>
            @endforeach
          </div>
        </div>

        <div class="tfa-actions">
          <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}">
            @csrf
            <button class="btn-pt btn-pt--light" type="submit">Generate new recovery codes</button>
          </form>

          <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            @method('DELETE')
            <button class="tfa-disable" type="submit">Turn off two-factor authentication</button>
          </form>
        </div>
      @endif
    </section>
  </div>
</main>
@endsection
