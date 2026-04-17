<?php

use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CancelExpiredOrders;

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:cancel-expired', function () {
    $this->call(CancelExpiredOrders::class);
})->purpose('Cancel orders that have been in pending for too long');
