<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex,nofollow">
<title>@yield('title') | Patara Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,400;6..96,500&family=Outfit:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
@stack('styles')
</head>
<body class="ad-body">
<div class="ad-wrap">
@include('admin.partials.sidebar', ['active' => trim($__env->yieldContent('active'))])
<div class="ad-overlay"></div>
<div class="ad-main">
@include('admin.partials.topbar')
<main class="ad-content" id="main">
@if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
@yield('content')
</main>
<footer class="ad-foot">&copy; {{ date('Y') }} Patara Resort &amp; Spa Hotel — Admin</footer>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
