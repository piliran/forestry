<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'can' => auth()->check() ? $this->getUserPrivileges() : [],
            'roles' => auth()->check() ? $this->getUserRoles() : [],
        ];
    }

    /**
     * Get the user's permissions as a flattened array.
     *
     * @return array
     */
    // protected function getUserPermissions(): array
    // {
    //     return auth()->user()
    //         ->loadMissing('roles.permissions')
    //         ->roles
    //         ->flatMap(fn ($role) => $role->permissions)
    //         ->mapWithKeys(fn ($permission) => [$permission->name => true])
    //         ->toArray();
    // }

    protected function getUserPrivileges(): array
    {
        return auth()->user()
            ->loadMissing('roles.privileges')
            ->roles
            ->flatMap(fn ($role) => $role->privileges)
            ->mapWithKeys(fn ($privilege) => [$privilege->privilege => true])
            ->toArray();
    }


    /**
     * Get the user's roles.
     *
     * @return array
     */
    protected function getUserRoles(): array
    {
        return auth()->user()
            ->roles()
            ->pluck('name') // Retrieve only the role names
            ->toArray();
    }
}
