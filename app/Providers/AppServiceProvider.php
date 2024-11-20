<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Zone;
use App\Policies\UserPolicy;

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

        Gate::before(function (User $user, string $ability) {
            if ($user->isAdministrator()) {
                return true;
            }
        });

        Gate::policy(User::class, UserPolicy::class);

        // Gate::after(function (User $user, string $ability, bool|null $result, mixed $arguments) {
        //     if ($user->isAdministrator()) {
        //         return true;
        //     }
        // });

        Gate::define('edit-user', [UserPolicy::class, 'edit_user']);
        // Gate::define('edit-user', function (User $user, Zone $zone) {
        //     return $user->id === $zone->user_id;
        // });
    }
}
