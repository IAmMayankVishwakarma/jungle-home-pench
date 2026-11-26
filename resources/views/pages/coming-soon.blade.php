@extends('layouts.blank')

@section('title', 'Coming Soon')
@section('body_class', 'page-coming-soon')

@section('content')
<header class="pt-header"><div class="container"><a class="pt-logo" href="{{ url('/') }}"><span class="pt-logo__name">PATARA</span><span class="pt-logo__sub">RESORT &amp; SPA HOTEL</span></a><a class="pt-cart" href="{{ url('cart') }}" aria-label="Cart"><svg class="ic " width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h3l2.5 11h10l2-8H7"/><circle cx="10" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/></svg><span class="pt-cart__count">0</span></a></div></header><main class="hero hero--full bg-bed1 text-center"><div class="container hero__in"><h1 class="hero__title">Coming Soon!</h1><p>Our website is under construction</p><div class="cd" data-date="2027-01-01T00:00:00"><div><strong data-u="d">00</strong>Days</div><div><strong data-u="h">00</strong>Hours</div><div><strong data-u="m">00</strong>Minutes</div><div><strong data-u="s">00</strong>Seconds</div></div><a class="back-home" href="{{ url('/') }}"><svg class="ic " width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 12H4M10 6l-6 6 6 6"/></svg> Back to home</a></div></main>
@endsection
