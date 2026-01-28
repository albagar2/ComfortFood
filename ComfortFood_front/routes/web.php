<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::view('users', 'admin.users.index')->name('admin.users.index');
    Route::view('users/details', 'admin.users.show')->name('admin.users.show');
    Route::view('users/edit', 'admin.users.edit')->name('admin.users.edit');
});

Route::middleware(['auth'])->group(function () {
    Route::view('orders/history', 'orders.history')->name('orders.history');
    Route::view('orders/details', 'orders.details')->name('orders.details');
    Route::view('menu', 'menu.index')->name('menu.index');
    Route::view('menu/edit', 'menu.edit')->name('menu.edit');
    Route::view('restaurant', 'restaurant.show')->name('restaurant.show');
    Route::view('customer/orders/details', 'customer.orders.details')->name('customer.orders.details');
    Route::view('customer/orders/history', 'customer.orders.history')->name('customer.orders.history');
});

require __DIR__.'/settings.php';
