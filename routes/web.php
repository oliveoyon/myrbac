<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\FormalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboard.index');

Route::prefix('mne')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('permission:Admin Dashboard');

    Route::get('category-management', [CategoryController::class, 'categories'])->name('dashboard.categories')->middleware('permission:View Categories');  
    Route::post('categories', [CategoryController::class, 'districtAdd'])->name('categories.add')->middleware('permission:Add Category');  
    Route::put('categories/{category}', [CategoryController::class, 'districtUpdate'])->name('categories.update')->middleware('permission:Edit Category');  
    Route::delete('categories/{category}', [CategoryController::class, 'districtDelete'])->name('categories.delete')->middleware('permission:Delete Category');  

    Route::get('district-management', [DashboardController::class, 'districts'])->name('dashboard.districts')->middleware('permission:View Districts');  
    Route::post('districts', [DashboardController::class, 'districtAdd'])->name('districts.add')->middleware('permission:Add District');  
    Route::put('districts/{district}', [DashboardController::class, 'districtUpdate'])->name('districts.update')->middleware('permission:Edit District');  
    Route::delete('districts/{district}', [DashboardController::class, 'districtDelete'])->name('districts.delete')->middleware('permission:Delete District');  

    Route::get('pngo-management', [DashboardController::class, 'pngos'])->name('dashboard.pngos')->middleware('permission:View PNGOs');  
    Route::post('pngos', [DashboardController::class, 'pngoAdd'])->name('pngos.add')->middleware('permission:Add PNGO');  
    Route::put('pngos/{pngo}', [DashboardController::class, 'pngoUpdate'])->name('pngos.update')->middleware('permission:Edit PNGO');  
    Route::delete('pngos/{pngo}', [DashboardController::class, 'pngoDelete'])->name('pngos.delete')->middleware('permission:Delete PNGO');  

    Route::get('/user-management', [UserController::class, 'index'])->name('users.index')->middleware('permission:View Users');  
    Route::post('addUser', [UserController::class, 'addUser'])->name('addUser')->middleware('permission:Add User');  
    Route::post('getUserDetails', [UserController::class, 'getUserDetails'])->name('getUserDetails')->middleware('permission:View User Details');  
    Route::post('updateUserDetails', [UserController::class, 'updateUserDetails'])->name('updateUserDetails')->middleware('permission:Edit User');  
    Route::get('/users/{userId}/permissions', [UserController::class, 'viewUserPermissions'])->middleware('permission:View User Permissions');  
    Route::get('/users/{id}/edit-permissions', [UserController::class, 'editPermissions'])->name('users.edit-permissions')->middleware('permission:Edit User Permissions');  
    Route::post('/users/{id}/update-permissions', [UserController::class, 'updatePermissions'])->name('users.update-permissions')->middleware('permission:Update User Permissions');  

    Route::get('role-management', [RoleController::class, 'roles'])->name('dashboard.roles')->middleware('permission:View Roles');  
    Route::post('roles', [RoleController::class, 'roleAdd'])->name('roles.add')->middleware('permission:Add Role');  
    Route::put('roles/{role}', [RoleController::class, 'roleUpdate'])->name('roles.update')->middleware('permission:Edit Role');  
    Route::delete('roles/{role}', [RoleController::class, 'roleDelete'])->name('roles.delete')->middleware('permission:Delete Role');  

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.list')->middleware('permission:View Permissions');  
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.add')->middleware('permission:Add Permission');  
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:Edit Permission');  
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.delete')->middleware('permission:Delete Permission');  

    Route::get('roles-and-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions')->middleware('permission:Manage Role Permissions');  
    Route::get('role/{role}/permissions', [RolePermissionController::class, 'viewPermissions'])->middleware('permission:View Role Permissions');  
    Route::get('role/{role}/edit-permissions', [RolePermissionController::class, 'editPermissions'])->middleware('permission:Edit Role Permissions');  
    Route::post('role/update-permissions/{roleId}', [RolePermissionController::class, 'updatePermissions'])->middleware('permission:Update Role Permissions');  

    Route::get('court-police-prison', [FormalController::class, 'index'])->name('form.index')->middleware('permission:View Formal Cases Form');
    Route::post('formAction', [FormalController::class, 'courtPolicePrison'])->name('formaction')->middleware('permission:Create Formal Case');
    Route::get('/edit-case', [FormalController::class, 'editCaseForm'])->name('edit-case.get')->middleware('permission:View Edit Formal Case Form');
    Route::post('/edit-case', [FormalController::class, 'editCase'])->name('edit-case.post')->middleware('permission:Edit Formal Case');
    Route::post('editformaction', [FormalController::class, 'editCourtPolicePrison'])->name('editformaction')->middleware('permission:Update Formal Case Details');
    Route::get('/edit-file', [FormalController::class, 'fileCaseForm'])->name('edit-file.get')->middleware('permission:View File Formal Case Form');
    Route::post('/edit-file', [FormalController::class, 'fileCase'])->name('edit-file.post')->middleware('permission:File Formal Case');
    Route::get('/import-formal-cases', [FormalController::class, 'importView'])->name('import.view')->middleware('permission:View Formal Case Import Page');
    Route::post('/import-formal-cases', [FormalController::class, 'import'])->name('import.store')->middleware('permission:Import Formal Cases');
    Route::get('/formal-cases/export', [ReportController::class, 'exportExcel'])->name('formal.cases.export')->middleware('permission:Export Formal Cases');
    Route::post('/dashboard/search', [ReportController::class, 'search'])->name('dashboard.search')->middleware('permission:Search Dashboard Reports');
    

    // Reporting purpose

    Route::post('/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate-pdf');
    Route::post('/generate-pdf-chart', [ReportController::class, 'generatePdfChart'])->name('generate-pdf-chart');
    Route::post('/generate-form', [ReportController::class, 'generateForm'])->name('generate-form');

    Route::get('district-list-report', [ReportController::class, 'district_report'])->name('district-list-report')->middleware('permission:View District List Report');
    Route::get('case-list', [ReportController::class, 'districtWiseCaselist'])->name('case_list')->middleware('permission:View Case List Report');
    Route::post('/case-list', [ReportController::class, 'districtWiseCaselistDetail'])->name('case_list1')->middleware('permission:View Case List Report Details');
    Route::get('/intervention-report', [ReportController::class, 'customReport'])->name('customReport')->middleware('permission:View Intervention Report Page');
    Route::post('/custom-report', [ReportController::class, 'generateCustomReport'])->name('custom.report.generate')->middleware('permission:Generate Custom Report');
    Route::get('/getFormalCaseStats', [ReportController::class, 'getFormalCaseStats'])->name('getFormalCaseStats')->middleware('permission:View Formal Case Statistics');
    Route::get('/district-summery', [ReportController::class, 'districtSummery'])->name('district.summery')->middleware('permission:View District Summary Report');
    Route::get('/pngo-summery', [ReportController::class, 'pngoSummery'])->name('pngo.summery')->middleware('permission:View PNGO Summary Report');
    
    
    
    Route::get('/generate-pdfs', [PDFController::class, 'generatePDF']);



    

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
