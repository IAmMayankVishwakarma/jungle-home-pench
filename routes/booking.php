<?php

use Illuminate\Support\Facades\Route;

Route::get('bookings', function () {
    return view('admin.pages.bookings');
})->middleware('permission:bookings.view,bookings.manage')
    ->name('bookings.index');

Route::get('calendar', function () {
    return view('admin.pages.calendar');
})->middleware('permission:bookings.view,bookings.manage')
    ->name('calendar.index');