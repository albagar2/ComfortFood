<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Client\ClientDashboard;
use App\Livewire\Client\CartPage;
use App\Livewire\Client\Favorites;
use App\Livewire\Client\MenuShow;
use App\Livewire\Client\Support as ClientSupport;
use App\Livewire\Restaurant\RestaurantDashboard;
use App\Livewire\Restaurant\RestaurantProfile;
use App\Livewire\Restaurant\Statistics;
use App\Livewire\Restaurant\Support as RestaurantSupport;
use App\Livewire\Admin\UserList;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Orders\OrderDetails;
use App\Livewire\Orders\OrderHistory;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard redirection logic can be handled here or in a controller
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) return redirect()->route('admin.users');
        if ($user->hasRole('restaurante')) return redirect()->route('restaurant.dashboard');
        return redirect()->route('client.dashboard');
    })->name('dashboard');

    // Client Routes
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', ClientDashboard::class)->name('dashboard');
        Route::get('/cart', CartPage::class)->name('cart');
        Route::get('/favorites', Favorites::class)->name('favorites');
        Route::get('/menu/{id}', MenuShow::class)->name('menu.show');
        Route::get('/support', ClientSupport::class)->name('support');
    });

    // Restaurant Routes
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        Route::get('/dashboard', RestaurantDashboard::class)->name('dashboard');
        Route::get('/profile', RestaurantProfile::class)->name('profile');
        Route::get('/support', RestaurantSupport::class)->name('support');
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/users', UserList::class)->name('users');
    });

    // Orders
    Route::get('/orders/history', OrderHistory::class)->name('orders.history');
    Route::get('/orders/{id}', OrderDetails::class)->name('orders.details');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/password', Password::class)->name('password');
        Route::get('/appearance', Appearance::class)->name('appearance');
        Route::get('/two-factor', TwoFactor::class)->name('two-factor');
    });
});

require __DIR__.'/fortify.php';
