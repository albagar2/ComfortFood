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
    Route::get('orders/details/{order}', \App\Livewire\Orders\OrderDetails::class)->name('orders.details');
    Route::get('menu', \App\Livewire\Menus\MenuManagement::class)->name('menu.index');
    Route::get('menu/show/{menu}', \App\Livewire\MenuShow::class)->name('menu.show');
    Route::get('menu/edit/{menu?}', \App\Livewire\Menus\MenuForm::class)->name('menu.edit');
    Route::get('restaurant/{restaurante}', \App\Livewire\RestaurantProfile::class)->name('restaurant.show');
    Route::view('customer/orders/details', 'customer.orders.details')->name('customer.orders.details');
});

require __DIR__.'/settings.php';
