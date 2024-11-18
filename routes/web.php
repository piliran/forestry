<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RoleCategoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CrimeController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\StationController;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;



Route::post('/role-categories/bulk-delete', [RoleCategoryController::class, 'bulkDelete']);
Route::post('/roles/bulk-delete', [RoleController::class, 'batchDelete']);

Route::resource('role-categories', RoleCategoryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::resource('roles', RoleController::class)->middleware([HandlePrecognitiveRequests::class]);


Route::resource('crimes', CrimeController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/crimes/bulk-delete', [CrimeController::class, 'batchDelete']);

Route::resource('districts', DistrictController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/districts/bulk-delete', [DistrictController::class, 'batchDelete']);


Route::resource('stations', StationController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/stations/bulk-delete', [StationController::class, 'batchDelete']);

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

Route::get('/species-list', function () {
    return Inertia::render('Department/SpeciesList');
});

Route::get('/species-types', function () {
    return Inertia::render('Department/SpeciesTypes');
});


Route::get('/users-list', function () {
    return Inertia::render('User/UserList');
});
Route::get('/user-roles', function () {
    return Inertia::render('User/UserRoles');
});
// Route::get('/roles', function () {
//     return Inertia::render('User/Roles');
// });
Route::get('/permissions', function () {
    return Inertia::render('User/Permissions');
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
