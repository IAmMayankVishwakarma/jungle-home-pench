@extends('admin.layouts.auth')

@section('title', 'Two-factor authentication')

@push('styles')
<link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="auth bg-auth">
  <section class="auth__box tfa-challenge" aria-labelledby="tfa-challenge-heading">
    <a class="ad-logo ad-logo--c tfa-challenge__brand" href="{{ route('home') }}" aria-label="Patara Resort home">
      <span class="ad-logo__n">PATARA</span>
      <span class="ad-logo__s">SECURE SIGN-IN</span>
    </a>

    <div class="tfa-challenge__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 3 20 6v5c0 5-3.4 8.3-8 10-4.6-1.7-8-5-8-10V6l8-3z"></path>
        <path d="m9 12 2 2 4-4"></path>
      </svg>
    </div>

    <p class="tfa-challenge__eyebrow">Account security</p>
    <h1 id="tfa-challenge-heading">Two-factor authentication</h1>
    <p class="tfa-challenge__intro">Enter the latest 6-digit code from your authenticator app. If you just confirmed 2FA, wait for the code to refresh before signing in.</p>

    @if ($errors->any())
      <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form class="tfa-challenge__form" method="POST" action="{{ route('two-factor.login.store') }}">
      @csrf
      <label for="tfa-login-code">Authenticator code</label>
      <input
        id="tfa-login-code"
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
      <button class="btn-ad w-100 justify-content-center" type="submit">Verify and continue</button>
    </form>

    <div class="tfa-challenge__divider"><span>or use a recovery code</span></div>

    <form class="tfa-challenge__form" method="POST" action="{{ route('two-factor.login.store') }}">
      @csrf
      <label for="tfa-recovery-code">Recovery code</label>
      <input
        id="tfa-recovery-code"
        type="text"
        name="recovery_code"
        autocomplete="off"
        autocapitalize="none"
        spellcheck="false"
        required
      >
      <button class="btn-ad btn-ad--ghost w-100 justify-content-center" type="submit">Use recovery code</button>
    </form>
  </section>
</main>
@endsection
