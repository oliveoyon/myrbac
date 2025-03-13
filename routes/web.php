<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
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
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Route::get('category-management', [CategoryController::class, 'categories'])->name('dashboard.categories');
    // Route::post('categories', [CategoryController::class, 'districtAdd'])->name('categories.add');  // Add Category
    // Route::put('categories/{category}', [CategoryController::class, 'districtUpdate'])->name('categories.update');  // Update Category
    // Route::delete('categories/{category}', [CategoryController::class, 'districtDelete'])->name('categories.delete');  // Delete Category

    // Route::get('district-management', [DashboardController::class, 'districts'])->name('dashboard.districts');
    // Route::post('districts', [DashboardController::class, 'districtAdd'])->name('districts.add');  // Add District
    // Route::put('districts/{district}', [DashboardController::class, 'districtUpdate'])->name('districts.update');  // Update District
    // Route::delete('districts/{district}', [DashboardController::class, 'districtDelete'])->name('districts.delete');  // Delete District

    // Route::get('pngo-management', [DashboardController::class, 'pngos'])->name('dashboard.pngos');
    // Route::post('pngos', [DashboardController::class, 'pngoAdd'])->name('pngos.add');  // Add District
    // Route::put('pngos/{pngo}', [DashboardController::class, 'pngoUpdate'])->name('pngos.update');  // Update District
    // Route::delete('pngos/{pngo}', [DashboardController::class, 'pngoDelete'])->name('pngos.delete');  // Delete District

    // Route::get('/user-management', [UserController::class, 'index'])->name('users.index'); // List all users
    // Route::post('addUser', [UserController::class, 'addUser'])->name('addUser');
    // Route::post('getUserDetails', [UserController::class, 'getUserDetails'])->name('getUserDetails');
    // Route::post('updateUserDetails', [UserController::class, 'updateUserDetails'])->name('updateUserDetails');
    // Route::get('/users/{userId}/permissions', [UserController::class, 'viewUserPermissions']);
    // Route::get('/users/{id}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions');
    // Route::post('/users/{id}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions');

    // Route::get('role-management', [RoleController::class, 'roles'])->name('dashboard.roles');
    // Route::post('roles', [RoleController::class, 'roleAdd'])->name('roles.add');  // Add District
    // Route::put('roles/{role}', [RoleController::class, 'roleUpdate'])->name('roles.update');  // Update District
    // Route::delete('roles/{role}', [RoleController::class, 'roleDelete'])->name('roles.delete');  // Delete District

    // Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.list');
    // Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.add');
    // Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    // Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.delete');

    // // Assign Roles and Permission
    // Route::get('roles-and-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions');
    // Route::get('role/{role}/permissions', [RolePermissionController::class, 'viewPermissions']);
    // Route::get('role/{role}/edit-permissions', [RolePermissionController::class, 'editPermissions']);
    // Route::post('role/update-permissions/{roleId}', [RolePermissionController::class, 'updatePermissions']);

    //  With the permissions middleware

Route::get('category-management', [CategoryController::class, 'categories'])->name('dashboard.categories')->middleware('role_or_permission:View Categories');  
Route::post('categories', [CategoryController::class, 'districtAdd'])->name('categories.add')->middleware('role_or_permission:Add Category');  
Route::put('categories/{category}', [CategoryController::class, 'districtUpdate'])->name('categories.update')->middleware('role_or_permission:Edit Category');  
Route::delete('categories/{category}', [CategoryController::class, 'districtDelete'])->name('categories.delete')->middleware('role_or_permission:Delete Category');  

Route::get('district-management', [DashboardController::class, 'districts'])->name('dashboard.districts')->middleware('role_or_permission:View Districts');  
Route::post('districts', [DashboardController::class, 'districtAdd'])->name('districts.add')->middleware('role_or_permission:Add District');  
Route::put('districts/{district}', [DashboardController::class, 'districtUpdate'])->name('districts.update')->middleware('role_or_permission:Edit District');  
Route::delete('districts/{district}', [DashboardController::class, 'districtDelete'])->name('districts.delete')->middleware('role_or_permission:Delete District');  

Route::get('pngo-management', [DashboardController::class, 'pngos'])->name('dashboard.pngos')->middleware('role_or_permission:View PNGOs');  
Route::post('pngos', [DashboardController::class, 'pngoAdd'])->name('pngos.add')->middleware('role_or_permission:Add PNGO');  
Route::put('pngos/{pngo}', [DashboardController::class, 'pngoUpdate'])->name('pngos.update')->middleware('role_or_permission:Edit PNGO');  
Route::delete('pngos/{pngo}', [DashboardController::class, 'pngoDelete'])->name('pngos.delete')->middleware('role_or_permission:Delete PNGO');  

Route::get('/user-management', [UserController::class, 'index'])->name('users.index')->middleware('role_or_permission:View Users');  
Route::post('addUser', [UserController::class, 'addUser'])->name('addUser')->middleware('role_or_permission:Add User');  
Route::post('getUserDetails', [UserController::class, 'getUserDetails'])->name('getUserDetails')->middleware('role_or_permission:View User Details');  
Route::post('updateUserDetails', [UserController::class, 'updateUserDetails'])->name('updateUserDetails')->middleware('role_or_permission:Edit User');  
Route::get('/users/{userId}/permissions', [UserController::class, 'viewUserPermissions'])->middleware('role_or_permission:View User Permissions');  
Route::get('/users/{id}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions')->middleware('role_or_permission:Edit User Permissions');  
Route::post('/users/{id}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions')->middleware('role_or_permission:Update User Permissions');  

Route::get('role-management', [RoleController::class, 'roles'])->name('dashboard.roles')->middleware('role_or_permission:View Roles');  
Route::post('roles', [RoleController::class, 'roleAdd'])->name('roles.add')->middleware('role_or_permission:Add Role');  
Route::put('roles/{role}', [RoleController::class, 'roleUpdate'])->name('roles.update')->middleware('role_or_permission:Edit Role');  
Route::delete('roles/{role}', [RoleController::class, 'roleDelete'])->name('roles.delete')->middleware('role_or_permission:Delete Role');  

Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.list')->middleware('role_or_permission:View Permissions');  
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.add')->middleware('role_or_permission:Add Permission');  
Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('role_or_permission:Edit Permission');  
Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.delete')->middleware('role_or_permission:Delete Permission');  

Route::get('roles-and-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions')->middleware('role_or_permission:Manage Role Permissions');  
Route::get('role/{role}/permissions', [RolePermissionController::class, 'viewPermissions'])->middleware('role_or_permission:View Role Permissions');  
Route::get('role/{role}/edit-permissions', [RolePermissionController::class, 'editPermissions'])->middleware('role_or_permission:Edit Role Permissions');  
Route::post('role/update-permissions/{roleId}', [RolePermissionController::class, 'updatePermissions'])->middleware('role_or_permission:Update Role Permissions');  



    

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
