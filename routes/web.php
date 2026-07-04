<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;

use App\Http\Controllers\Perawat\NurseDashboardController;
use App\Http\Controllers\Perawat\PatientController as NursePatientController;
use App\Http\Controllers\Perawat\CpptController;

use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorCpptController;

/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Semua Route Setelah Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('admin.dashboard');

    Route::resource('/admin/users', UserController::class)
        ->names('admin.users');

    Route::resource('/admin/wards', WardController::class)
        ->names('admin.wards');

    Route::resource('/admin/rooms', RoomController::class)
        ->names('admin.rooms');

    Route::resource('/admin/patients', AdminPatientController::class)
        ->names('admin.patients');

    /*
    |--------------------------------------------------------------------------
    | PERAWAT
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/perawat/dashboard',
        [NurseDashboardController::class, 'index']
    )->name('perawat.dashboard');

    Route::prefix('perawat')
        ->name('perawat.')
        ->group(function () {

            Route::resource(
                'patients',
                NursePatientController::class
            );

            Route::resource(
                'patients.cppts',
                CpptController::class
            );

            Route::resource(
                'patients.monitorings',
                \App\Http\Controllers\Perawat\MonitoringController::class
            );
        });

    /*
    |--------------------------------------------------------------------------
    | DOKTER
    |--------------------------------------------------------------------------
    */

    Route::prefix('doctor')
        ->name('doctor.')
        ->group(function () {

            Route::get(
                '/dashboard',
                [DoctorDashboardController::class, 'index']
            )->name('dashboard');

            Route::resource(
                'cppts',
                DoctorCpptController::class
            )->only([
                'index',
                'show',
                'update'
            ]);
        });
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
