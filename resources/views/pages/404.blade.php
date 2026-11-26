@extends('layouts.bare')

@section('title', '404')
@section('body_class', 'page-404')

@section('content')
<main class="hero hero--full bg-bed1 text-center"><div class="container hero__in"><p class="e404">404</p><h1 class="hero__title">We Can’t Find That Page!</h1><p>The page you are looking for was moved, removed, renamed or never existed.</p><form class="search404" role="search" action="#"><input type="search" placeholder="Search.." aria-label="Search"><button type="submit">Search</button></form></div></main>
@endsection
