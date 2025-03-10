<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;

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

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.list');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.add');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.delete');

    // Assign Roles and Permission
    Route::get('roles-and-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions');
    Route::get('role/{role}/permissions', [RolePermissionController::class, 'viewPermissions']);
    Route::get('role/{role}/edit-permissions', [RolePermissionController::class, 'editPermissions']);
    Route::post('role/{role}/update-permissions', [RolePermissionController::class, 'updatePermissions']);
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
