<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {return view('pages.index');})->name('home');
Route::get('/about', function () {return view('pages.about');})->name('about');
Route::get('/rooms', function () {return view('pages.rooms');})->name('rooms');
Route::get('/room-details', function () {return view('pages.room-details');})->name('room-details');
Route::get('/services', function () {return view('pages.services');})->name('services');
Route::get('/restaurant', function () {return view('pages.restaurant');})->name('restaurant');
Route::get('/image-gallery', function () {return view('pages.image-gallery');})->name('image-gallery');
Route::get('/faq', function () {return view('pages.faq');})->name('faq');
Route::get('/cart', function () {return view('pages.cart');})->name('cart');
Route::get('/coming-soon', function () {return view('pages.coming-soon');})->name('coming-soon');
Route::get('/blog-grid', function () {return view('pages.blog-grid');})->name('blog-grid');
Route::get('/contact', function () {return view('pages.contact');})->name('contact');

// Google OAuth routes
Route::middleware('guest')->group(function () {

    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');

});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

     Route::get('/auth/google/link', [GoogleController::class, 'link'])
        ->name('google.link');

    Route::get('/auth/google/link/callback', [GoogleController::class, 'linkCallback'])
        ->name('google.link.callback');

});

// fallback route for any undefined routes
Route::fallback(function () {
    return view('pages.404');
});