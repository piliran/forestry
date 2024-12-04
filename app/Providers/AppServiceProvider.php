<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Zone;
use App\Models\Area;
use App\Models\Team;
use App\Models\UserPermissiom;
use App\Models\UserRole;

use App\Models\City;
use App\Models\Arrest;
use App\Models\Confiscate;
use App\Models\Country;
use App\Models\Crime;
use App\Models\Department;
use App\Models\District;
use App\Models\Encroached;
use App\Models\Operation;
use App\Models\OperationType;
use App\Models\Permissiom;
use App\Models\Role;
use App\Models\RoleCategory;
use App\Models\RoleToPermission;
use App\Models\Route;
use App\Models\RouteType;
use App\Models\Schedule;
use App\Models\Species;
use App\Models\SpeciesCategory;
use App\Models\Station;
use App\Models\StationToOperation;
use App\Models\Suspect;

use App\Policies\UserPolicy;
use App\Policies\ZonePolicy;
use App\Policies\AreaPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPermissionPolicy;
use App\Policies\UserRolePolicy;
use App\Policies\CityPolicy;
use App\Policies\ArrestPolicy;
use App\Policies\ConfiscatePolicy;
use App\Policies\CountryPolicy;
use App\Policies\CrimePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DistrictPolicy;
use App\Policies\EncroachedPolicy;
use App\Policies\OperationPolicy;
use App\Policies\OperationTypePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\RoleCategoryPolicy;
use App\Policies\RoleToPermissionPolicy;
use App\Policies\RoutePolicy;
use App\Policies\RouteTypePolicy;
use App\Policies\SchedulePolicy;
use App\Policies\SpeciesPolicy;
use App\Policies\SpeciesCategoryPolicy;
use App\Policies\StationPolicy;
// use App\Policies\StationToOperationPolicy;
use App\Policies\SuspectPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate: Before method for admin check
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdministrator()) {
                return true; // Admin has unrestricted access
            }
        });

        // Register policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Zone::class, ZonePolicy::class);
        Gate::policy(Area::class, AreaPolicy::class);
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(UserPermissiom::class, UserPermissionPolicy::class);
        Gate::policy(UserRole::class, UserRolePolicy::class);

        Gate::policy(City::class, CityPolicy::class);
        Gate::policy(Arrest::class, ArrestPolicy::class);
        Gate::policy(Confiscate::class, ConfiscatePolicy::class);
        Gate::policy(Country::class, CountryPolicy::class);
        Gate::policy(Crime::class, CrimePolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(District::class, DistrictPolicy::class);
        Gate::policy(Encroached::class, EncroachedPolicy::class);
        Gate::policy(Operation::class, OperationPolicy::class);
        Gate::policy(OperationType::class, OperationTypePolicy::class);
        Gate::policy(Permissiom::class, PermissionPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(RoleCategory::class, RoleCategoryPolicy::class);
        Gate::policy(RoleToPermission::class, RoleToPermissionPolicy::class);
        Gate::policy(Route::class, RoutePolicy::class);
        Gate::policy(RouteType::class, RouteTypePolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);
        Gate::policy(Species::class, SpeciesPolicy::class);
        Gate::policy(SpeciesCategory::class, SpeciesCategoryPolicy::class);
        Gate::policy(Station::class, StationPolicy::class);
        // Gate::policy(StationToOperation::class, StationToOperationPolicy::class);
        Gate::policy(Suspect::class, SuspectPolicy::class);
    }
}
