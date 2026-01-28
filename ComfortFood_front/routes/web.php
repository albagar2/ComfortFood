<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Orders\OrderHistory;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


use App\Http\Controllers\Admin\UserController as AdminUserController;

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::view('users', 'admin.users.index')->name('admin.users.index');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
});

Route::middleware(['auth'])->group(function () {
    Route::get('orders/history', OrderHistory::class)->name('orders.history');
    Route::view('orders/details', 'orders.details')->name('orders.details');
    Route::view('menu', 'menu.index')->name('menu.index');
    Route::view('menu/edit', 'menu.edit')->name('menu.edit');
    Route::view('restaurant', 'restaurant.show')->name('restaurant.show');
    Route::view('customer/orders/details', 'customer.orders.details')->name('customer.orders.details');
});

require __DIR__.'/settings.php';
