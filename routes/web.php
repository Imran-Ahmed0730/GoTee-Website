<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DemoRequestController;
use App\Http\Controllers\Admin\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe.store');
Route::post('/request/demo/send', [DemoRequestController::class, 'store'])->name('request.demo.send');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
