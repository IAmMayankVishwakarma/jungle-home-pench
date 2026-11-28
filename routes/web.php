<?php

use Illuminate\Support\Facades\Route;


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



// fallback route for any undefined routes
Route::fallback(function () {
    return view('pages.404');
});