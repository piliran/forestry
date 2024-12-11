<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RoleCategoryController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\SpeciesCategoryController;
use App\Http\Controllers\SpeciesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TablePrivilegesController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserPermissionsController;

use App\Http\Controllers\OperationTypeController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\OperationToTeamController;
use App\Http\Controllers\FunderController;

use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserToTeamController;
use App\Http\Controllers\UserPrivilegeController;


use App\Http\Controllers\ZoneController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\EncroachedController;
use App\Http\Controllers\SuspectController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\RouteTypeController;

use App\Http\Controllers\CrimeController;
use App\Http\Controllers\OffenseController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\ConfiscateController;
use App\Http\Controllers\ArrestController;

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;


Route::post('/zones/bulk-delete', [ZoneController::class, 'batchDelete']);
Route::resource('zones', ZoneController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::resource('department', DepartmentController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/department/bulk-delete', [DepartmentController::class, 'batchDelete']);


Route::post('/countries/bulk-delete', [CountryController::class, 'batchDelete']);
Route::resource('countries', CountryController::class)->middleware([HandlePrecognitiveRequests::class]);


Route::post('/area-list/bulk-delete', [AreaController::class, 'batchDelete']);
Route::resource('area-list', AreaController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::post('/encroached-areas/bulk-delete', [EncroachedController::class, 'batchDelete']);
Route::resource('encroached-areas', EncroachedController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::post('/confiscates/bulk-delete', [ConfiscateController::class, 'batchDelete']);
Route::post('/update-confiscate', [ConfiscateController::class, 'updateConfiscate']);
Route::resource('confiscates', ConfiscateController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::post('/arrests/bulk-delete', [ArrestController::class, 'batchDelete']);
Route::resource('arrests', ArrestController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::post('/schedules/bulk-delete', [ScheduleController::class, 'batchDelete']);
Route::resource('schedules', ScheduleController::class)->middleware([HandlePrecognitiveRequests::class]);




Route::post('/role-categories/bulk-delete', [RoleCategoryController::class, 'bulkDelete']);
Route::post('/roles/bulk-delete', [RoleController::class, 'batchDelete']);

Route::resource('role-categories', RoleCategoryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::resource('roles', RoleController::class)->middleware([HandlePrecognitiveRequests::class]);

Route::resource('users', UserController::class)->middleware([HandlePrecognitiveRequests::class]);


Route::resource('crimes', CrimeController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/crimes/bulk-delete', [CrimeController::class, 'batchDelete']);

Route::resource('offenses', OffenseController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/offenses/bulk-delete', [OffenseController::class, 'batchDelete']);


Route::resource('districts', DistrictController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/districts/bulk-delete', [DistrictController::class, 'batchDelete']);


Route::resource('stations', StationController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/stations/bulk-delete', [StationController::class, 'batchDelete']);


Route::resource('countries', CountryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/countries/bulk-delete', [CountryController::class, 'batchDelete']);

Route::resource('permissions', PermissionController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/permissions/bulk-delete', [PermissionController::class, 'batchDelete']);

Route::resource('table-permissions', PermissionController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/table-permissions/bulk-delete', [PermissionController::class, 'batchDelete']);

Route::resource('privileges', TablePrivilegesController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/privileges/bulk-delete', [TablePrivilegesController::class, 'batchDelete']);


Route::resource('user-role', UserRoleController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/user-role/bulk-delete', [UserRoleController::class, 'batchDelete']);

Route::resource('user-permission', UserPermissionsController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/user-permission/bulk-delete', [UserPermissionsController::class, 'batchDelete']);

Route::resource('user-privilege', UserPrivilegeController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/user-privilege/bulk-delete', [UserPrivilegeController::class, 'batchDelete']);

Route::resource('species-types', SpeciesCategoryController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/species-types/bulk-delete', [SpeciesCategoryController::class, 'batchDelete']);

Route::resource('species-list', SpeciesController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/species-list/bulk-delete', [SpeciesController::class, 'batchDelete']);


Route::resource('suspects', SuspectController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/suspects/bulk-delete', [SuspectController::class, 'batchDelete']);
Route::post('/update-suspect', [SuspectController::class, 'updateSuspect']);
Route::get('/add-suspect', [SuspectController::class, 'create']);



Route::resource('route-types', RouteTypeController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/route-types/bulk-delete', [RouteTypeController::class, 'batchDelete']);

Route::resource('route-list', RouteController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/route-list/bulk-delete', [RouteController::class, 'batchDelete']);

Route::resource('operations-list', OperationController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/operations-list/bulk-delete', [OperationController::class, 'batchDelete']);

Route::resource('operations-types', OperationTypeController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/operations-types/delete-multiple', [OperationTypeController::class, 'batchDelete']);

Route::resource('schedules', ScheduleController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/schedules/delete-multiple', [ScheduleController::class, 'batchDelete']);

Route::resource('teams', TeamController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/teams/delete-multiple', [TeamController::class, 'batchDelete']);

Route::resource('staff', StaffController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/staff/delete-multiple', [StaffController::class, 'batchDelete']);
Route::post('/update-staff', [StaffController::class, 'update']);

Route::resource('team-members', UserToTeamController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/team-members/delete-multiple', [UserToTeamController::class, 'batchDelete']);
Route::post('/update-team-member', [UserToTeamController::class, 'update']);

Route::resource('team-operations', OperationToTeamController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/team-operations/delete-multiple', [OperationToTeamController::class, 'batchDelete']);
Route::post('/update-team-operations', [OperationToTeamController::class, 'update']);

Route::resource('funders', FunderController::class)->middleware([HandlePrecognitiveRequests::class]);
Route::post('/funders/delete-multiple', [FunderController::class, 'batchDelete']);

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/convictions', function () {
    return Inertia::render('Convictions/Index');
});

// Route::get('/crimes', function () {
//     return Inertia::render('Department/Crimes');
// });

// Route::get('/arrests', function () {
//     return Inertia::render('Department/Arrests');
// });

// Route::get('/stations', function () {
//     return Inertia::render('Department/Stations');
// });

// Route::get('/encroached-areas', function () {
//     return Inertia::render('Department/EncroachedAreas');
// });

// Route::get('/confiscates', function () {
//     return Inertia::render('Department/Confiscates');
// });

// Route::get('/route-list', function () {
//     return Inertia::render('Department/RouteList');
// });

// Route::get('/route-types', function () {
//     return Inertia::render('Department/RouteTypes');
// });

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
