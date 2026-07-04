<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Perawat\NurseDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

Route::middleware(['auth', 'role:perawat'])
    ->prefix('perawat')
    ->name('perawat.')
    ->group(function () {
        Route::get('/dashboard', [NurseDashboardController::class, 'index'])->name('dashboard');
    });
