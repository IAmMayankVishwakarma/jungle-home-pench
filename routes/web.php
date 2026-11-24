<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Users.Home');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});