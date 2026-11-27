@extends('layouts.app')

@section('title', 'Verify your email')
@section('body_class', 'page-verify-email')

@push('styles')
<link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<section class="hero hero--auth bg-bed1 ve-hero">
  <div class="container hero__in">
    <p class="hero__eyebrow">Member access</p>
    <h1 class="hero__title">Verify your email</h1>
  </div>
</section>

<main id="main" class="sec--tint ve-sec">
  <div class="container">
    <section class="ve-card" aria-labelledby="verify-card-title">
      <div class="ve-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="5" width="18" height="14" rx="1"></rect>
          <path d="m3 7 9 6 9-6"></path>
        </svg>
      </div>

      <p class="ve-eyebrow">One last step</p>
      <h2 id="verify-card-title">Check your inbox</h2>
      <p class="ve-copy">We sent a verification link to the email address on your account. Open it to confirm your email and continue.</p>

      @if (session('status') === 'verification-link-sent')
        <div class="ve-notice" role="status" aria-live="polite">
          <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m5 12 4 4L19 6"></path>
          </svg>
          <p>A fresh verification link has been sent. Please check your inbox.</p>
        </div>
      @endif

      @if ($errors->any())
        <div class="ve-alert" role="alert">{{ $errors->first() }}</div>
      @endif

      <div class="ve-actions">
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button class="btn-pt" type="submit">Resend verification email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="ve-logout" type="submit">Sign out</button>
        </form>
      </div>

      <p class="ve-footnote">If you don't see the email, check your spam folder.</p>
    </section>
  </div>
</main>
@endsection
