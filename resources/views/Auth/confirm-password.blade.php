@extends('layouts.app')

@section('title', 'Confirm your password')
@section('body_class', 'page-confirm-password')

@push('styles')
<link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<section class="hero hero--auth bg-bed1 pc-hero">
  <div class="container hero__in">
    <p class="hero__eyebrow">Account security</p>
    <h1 class="hero__title">Confirm your password</h1>
  </div>
</section>

<main id="main" class="sec--tint pc-sec">
  <div class="container">
    <section class="pc-card" aria-labelledby="confirm-password-heading">
      <div class="pc-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <rect x="4" y="10" width="16" height="11" rx="1"></rect>
          <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
          <circle cx="12" cy="15" r="1"></circle>
        </svg>
      </div>

      <p class="pc-eyebrow">Secure access</p>
      <h2 id="confirm-password-heading">Verify it's you</h2>
      <p class="pc-copy">For your security, please enter your current password before continuing to your account settings.</p>

      @if ($errors->any())
        <div class="pc-alert" role="alert">{{ $errors->first() }}</div>
      @endif

      <form class="pc-form" method="POST" action="{{ url('/user/confirm-password') }}">
        @csrf
        <label for="confirm-password">Current password</label>
        <input
          id="confirm-password"
          type="password"
          name="password"
          autocomplete="current-password"
          required
          autofocus
        >
        <button class="btn-pt" type="submit">Confirm and continue</button>
      </form>
    </section>
  </div>
</main>
@endsection
