<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
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

        // Role module
        Route::resource('role', RoleController::class)->except(['show']);
        Route::controller(RoleController::class)->name('role.')->group(function () {
            Route::get('/role/{id}/assign-permission', 'assignPermission')->name('assign.permission');
            Route::post('/role/assign-permission', 'assignPermissionSubmit')->name('assign.permission.submit');
        });

        // Permission module
        Route::resource('permission', PermissionController::class)->except(['show']);

        // Setting module
        Route::controller(SettingController::class)->name('setting.')->group(function () {
            Route::get('/setting', 'index')->name('index');
            Route::get('/setting/{section}', 'edit')->name('edit')->whereIn('section', [
                'general',
                'logos-favicon',
                'contact',
                'social-media',
                'smtp',
            ]);
            Route::patch('/setting/{section}', 'update')->name('update')->whereIn('section', [
                'general',
                'logos-favicon',
                'contact',
                'social-media',
                'smtp',
            ]);
        });

        // Blog module
        Route::patch('/blog/category/{category}/status', [BlogCategoryController::class, 'changeStatus'])->name('blog.category.status');
        Route::resource('blog/category', BlogCategoryController::class)->except(['show'])->names('blog.category');
        Route::patch('/blog/{blog}/status', [BlogController::class, 'changeStatus'])->name('blog.status');
        Route::resource('blog', BlogController::class)->except(['show']);

        // FAQ module
        Route::patch('/faq/category/{category}/status', [FaqCategoryController::class, 'changeStatus'])->name('faq.category.status');
        Route::resource('faq/category', FaqCategoryController::class)->except(['show'])->names('faq.category');
        Route::patch('/faq/{faq}/status', [FaqController::class, 'changeStatus'])->name('faq.status');
        Route::resource('faq', FaqController::class)->except(['show']);

        // Banner module
        Route::patch('/banner/{banner}/status', [BannerController::class, 'changeStatus'])->name('banner.status');
        Route::resource('banner', BannerController::class)->except(['show']);

        // Client module
        Route::patch('/client/{client}/status', [ClientController::class, 'changeStatus'])->name('client.status');
        Route::resource('client', ClientController::class);

        // Employee module
        Route::patch('/employee/{employee}/status', [EmployeeController::class, 'changeStatus'])->name('employee.status');
        Route::patch('/employee/{employee}/show-as-team', [EmployeeController::class, 'updateShowAsTeam'])->name('employee.show-as-team.update');
        Route::resource('employee', EmployeeController::class)->except(['show']);
    });
});
