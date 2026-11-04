<?php

use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->name('profile.')->group(function () {
        Route::get('/profile', 'edit')->name('edit');
        Route::patch('/profile', 'update')->name('update');
        Route::delete('/profile', 'destroy')->name('destroy');
    });

    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('role', RoleController::class)->except(['show']);
        Route::controller(RoleController::class)->name('role.')->group(function () {
            Route::get('/role/{id}/assign-permission', 'assignPermission')->name('assign.permission');
            Route::post('/role/assign-permission', 'assignPermissionSubmit')->name('assign.permission.submit');
        });

        Route::resource('permission', PermissionController::class)->except(['show']);

        Route::patch('/blog/category/{category}/status', [BlogCategoryController::class, 'changeStatus'])->name('blog.category.status');
        Route::resource('blog/category', BlogCategoryController::class)->except(['show'])->names('blog.category');
        Route::patch('/blog/{blog}/status', [BlogController::class, 'changeStatus'])->name('blog.status');
        Route::resource('blog', BlogController::class)->except(['show']);

        Route::patch('/faq/category/{category}/status', [FaqCategoryController::class, 'changeStatus'])->name('faq.category.status');
        Route::resource('faq/category', FaqCategoryController::class)->except(['show'])->names('faq.category');
        Route::patch('/faq/{faq}/status', [FaqController::class, 'changeStatus'])->name('faq.status');
        Route::resource('faq', FaqController::class)->except(['show']);
    });
});
