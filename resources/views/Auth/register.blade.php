@extends('layouts.app')

@section('title', 'Create Account')
@section('body_class', 'page-register')

@push('styles')
<link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<section class="hero hero--auth bg-bed1" style="min-height:clamp(340px,40vw,440px)">
  <div class="container hero__in">
    <p class="hero__eyebrow">Join us</p>
    <h1 class="hero__title">Create Account</h1>
  </div>
  <a class="hero__mouse" href="#main" aria-label="Scroll down"><span></span></a>
</section>

<main id="main" class="sec--tint au-sec">
  <div class="container">
    <div class="au-card">
      <aside class="au-side">
        {{-- Replace with a real image, ratio ~ 600x760 --}}
        <img src="{{ asset('assets/images/auth-side.svg') }}" width="600" height="760" alt="Patara Resort &amp; Spa Hotel suite" loading="lazy">
        <div>
          <h2>Welcome to Patara Luxury Hotel</h2>
          <p>Create an account to manage your stays and enjoy member benefits.</p>
          <ul class="au-perks">
            <li><svg class="ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-10"/></svg> Faster booking &amp; check-in</li>
            <li><svg class="ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-10"/></svg> View &amp; manage your reservations</li>
            <li><svg class="ic" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-10"/></svg> Exclusive member offers</li>
          </ul>
        </div>
      </aside>

      <div class="au-form">
        <h2>Sign up</h2>
        <p>Fill in your details to get started.</p>

        @if ($errors->any())
          <div class="au-alert" role="alert">Please fix the errors below and try again.</div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="au-validate" novalidate>
          @csrf

          <div class="row gx-4">
            <div class="col-md-6">
              <div class="au-field @error('name') is-invalid @enderror">
                <label for="name">Full name *</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" autocomplete="name" required autofocus>
                <span class="au-err {{ $errors->has('name') ? 'is-server' : '' }}">@error('name'){{ $message }}@else Please enter your name.@enderror</span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="au-field @error('phone') is-invalid @enderror">
                <label for="phone">Phone *</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+1 203 123 0606" autocomplete="tel" required>
                <span class="au-err {{ $errors->has('phone') ? 'is-server' : '' }}">@error('phone'){{ $message }}@else Please enter a phone number.@enderror</span>
              </div>
            </div>
          </div>

          <div class="au-field @error('email') is-invalid @enderror">
            <label for="email">Email *</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
            <span class="au-err {{ $errors->has('email') ? 'is-server' : '' }}">@error('email'){{ $message }}@else Please enter a valid email.@enderror</span>
          </div>

          <div class="au-field @error('password') is-invalid @enderror">
            <label for="password">Password *</label>
            <input id="password" type="password" name="password" placeholder="Minimum 8 characters" minlength="8" autocomplete="new-password" required>
            <button class="au-pw-btn" type="button" aria-label="Show password" aria-pressed="false"><svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></button>
            <div class="au-meter" data-s="0" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
            <div class="au-meter-t" aria-live="polite"></div>
            <span class="au-err {{ $errors->has('password') ? 'is-server' : '' }}">@error('password'){{ $message }}@else Password must be at least 8 characters.@enderror</span>
          </div>

          <div class="au-field">
            <label for="password_confirmation">Confirm password *</label>
            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Re-enter password" autocomplete="new-password" required>
            <button class="au-pw-btn" type="button" aria-label="Show password" aria-pressed="false"><svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></button>
            <span class="au-err">Passwords do not match.</span>
          </div>

          <label class="au-check" for="terms">
            <input id="terms" type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
            <span>I agree to the <a href="{{ url('terms') }}">Terms &amp; Conditions</a> and Privacy Policy.</span>
          </label>
          <span class="au-err au-terms-err {{ $errors->has('terms') ? 'is-server' : '' }}" style="margin:-16px 0 18px">@error('terms'){{ $message }}@else Please accept the terms to continue.@enderror</span>

          <button class="btn-pt au-btn" type="submit">Create account</button>
          <p class="au-alt">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
