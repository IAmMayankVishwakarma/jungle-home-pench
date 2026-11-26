@extends('layouts.app')

@section('title', 'Cart')
@section('body_class', 'page-cart')

@section('content')
<section class="hero bg-bed1 "><div class="container hero__in"><p class="hero__eyebrow">Resort &amp; Spa Hotel</p><h1 class="hero__title">Cart</h1></div></section><main id="main" class="sec sec--tint"><div class="container"><div class="notice"><svg class="ic " width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="1"/><path d="M3 10h18M8 3v4M16 3v4"/></svg> Your cart is currently empty.</div><div class="empty-cart"><svg class="ic " width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 4h3l2.5 11h10l2-8H7"/><circle cx="10" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/></svg><p>No products added to the shopping cart.</p><a class="btn-pt btn-pt--sm" href="{{ url('rooms') }}">Return to shop</a></div></div></main>
@endsection
