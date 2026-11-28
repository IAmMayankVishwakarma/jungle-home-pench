<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;


// Google OAuth routes
Route::middleware('guest')->group(function () {

    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');

});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/auth/google/link', [GoogleController::class, 'link'])
        ->name('google.link');

    Route::get('/auth/google/link/callback', [GoogleController::class, 'linkCallback'])
        ->name('google.link.callback');

});
