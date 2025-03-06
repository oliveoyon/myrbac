<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

Route::get('/', function () {
    return view('welcome');
});




// Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('mne')->middleware(['auth', 'verified'])->group(function () {
    // Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard'); //default dashboard of laravel

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::get('district-management', [DashboardController::class, 'districts'])->name('dashboard.districts');
    Route::post('districts', [DashboardController::class, 'districtAdd'])->name('districts.add');  // Add District
    Route::put('districts/{district}', [DashboardController::class, 'districtUpdate'])->name('districts.update');  // Update District
    Route::delete('districts/{district}', [DashboardController::class, 'districtDelete'])->name('districts.delete');  // Delete District

    Route::get('pngo-management', [DashboardController::class, 'pngos'])->name('dashboard.pngos');
    Route::post('pngos', [DashboardController::class, 'pngoAdd'])->name('pngos.add');  // Add District
    Route::put('pngos/{pngo}', [DashboardController::class, 'pngoUpdate'])->name('pngos.update');  // Update District
    Route::delete('pngos/{pngo}', [DashboardController::class, 'pngoDelete'])->name('pngos.delete');  // Delete District

    Route::get('/user-management', [UserController::class, 'index'])->name('users.index'); // List all users
    Route::post('addUser', [UserController::class, 'addUser'])->name('addUser');
    Route::post('getUserDetails', [UserController::class, 'getUserDetails'])->name('getUserDetails');
    Route::post('updateUserDetails', [UserController::class, 'updateUserDetails'])->name('updateUserDetails');
        // Route::post('deleteClass', [AcademicController::class, 'deleteClass'])->name('deleteClass');

        Route::get('role-management', [RoleController::class, 'roles'])->name('dashboard.roles');
        Route::post('roles', [RoleController::class, 'roleAdd'])->name('roles.add');  // Add District
        Route::put('roles/{role}', [RoleController::class, 'roleUpdate'])->name('roles.update');  // Update District
        Route::delete('roles/{role}', [RoleController::class, 'roleDelete'])->name('roles.delete');  // Delete District

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
