<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RoleCategoryController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\SpeciesCategoryController;
use App\Http\Controllers\SpecieController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserPermissionsController;

use App\Http\Controllers\ZoneController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AreaController;


use App\Http\Controllers\CrimeController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\StationController;

use App\Http\Controllers\UserController;
use App\Models\Species;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;


Route::post('/zones/bulk-delete', [ZoneController::class, 'batchDelete']);
Route::resource('zones', ZoneController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::resource('department', DepartmentController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/department/bulk-delete', [DepartmentController::class, 'batchDelete']);


Route::post('/countries/bulk-delete', [CountryController::class, 'batchDelete']);
Route::resource('countries', CountryController::class)->middleware([HandlePrecognitiveRequests::class]);


Route::post('/area-list/bulk-delete', [AreaController::class, 'batchDelete']);
Route::resource('area-list', AreaController::class)->middleware([HandlePrecognitiveRequests::class]);




Route::post('/role-categories/bulk-delete', [RoleCategoryController::class, 'bulkDelete']);
Route::post('/roles/bulk-delete', [RoleController::class, 'batchDelete']);

Route::resource('role-categories', RoleCategoryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::resource('roles', RoleController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::resource('users', UserController::class)->middleware([HandlePrecognitiveRequests::class]);


Route::resource('crimes', CrimeController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/crimes/bulk-delete', [CrimeController::class, 'batchDelete']);

Route::resource('districts', DistrictController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/districts/bulk-delete', [DistrictController::class, 'batchDelete']);


Route::resource('stations', StationController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/stations/bulk-delete', [StationController::class, 'batchDelete']);


Route::resource('countries', CountryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/countries/bulk-delete', [CountryController::class, 'batchDelete']);


Route::resource('permissions', PermissionController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/permissions/bulk-delete', [PermissionController::class, 'batchDelete']);

Route::resource('user-role', UserRoleController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/user-role/bulk-delete', [UserRoleController::class, 'batchDelete']);

Route::resource('user-permission', UserPermissionsController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/user-permission/bulk-delete', [UserPermissionsController::class, 'batchDelete']);

Route::resource('species-types', SpeciesCategoryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/species-types/bulk-delete', [SpeciesCategoryController::class, 'batchDelete']);


Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/districts', function () {
//     return Inertia::render('Department/Districts');
// });

// Route::get('/crimes', function () {
//     return Inertia::render('Department/Crimes');
// });

Route::get('/arrests', function () {
    return Inertia::render('Department/Arrests');
});

// Route::get('/stations', function () {
//     return Inertia::render('Department/Stations');
// });

Route::get('/encroached-areas', function () {
    return Inertia::render('Department/EncroachedAreas');
});

Route::get('/confiscates', function () {
    return Inertia::render('Department/Confiscates');
});

Route::get('/route-list', function () {
    return Inertia::render('Department/RouteList');
});

Route::get('/route-types', function () {
    return Inertia::render('Department/RouteTypes');
});

// Route::get('/species-list', function () {
//     return Inertia::render('Department/SpeciesList');
// });

// Route::get('/species-types', function () {
//     return Inertia::render('Department/SpeciesTypes');
// });


Route::get('/users-list', function () {
    return Inertia::render('User/UserList');
});
Route::get('/user-roles', function () {
    return Inertia::render('User/UserRoles');
});
// Route::get('/roles', function () {
//     return Inertia::render('User/Roles');
// });
// Route::get('/permissions', function () {
//     return Inertia::render('User/Permissions');
// });



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
