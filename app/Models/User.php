<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;

    protected $fillable = [
        'title',
        'name',
        'email',
        'gender',
        'DOB',
        'district_id',
        'city',
        'country',
        'position',
        'national_id',
        'phone',
        'account_status',
        'marital_status',
        'password',
        'current_team_id',
        'profile_photo_path',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function district()
    {
        return $this->belongsTo(District::class,'district_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    


//     public function hasPermission($permissionName)
// {
//     $rolePermissions = $this->roles()
//                             ->with('permissions')
//                             ->get()
//                             ->pluck('permissions')
//                             ->flatten()
//                             ->pluck('name')
//                             ->toArray();

//     $directPermissions = $this->permissions()->pluck('name')->toArray();

//     $allPermissions = array_unique(array_merge($rolePermissions, $directPermissions));

//     return in_array($permissionName, $allPermissions);
// }

public function hasPermissionTo($permission)
{
   
    if ($this->permissions->contains('name', $permission)) {
        return true;
    }

  
    foreach ($this->roles as $role) {
        if ($role->permissions->contains('name', $permission)) {
            return true;
        }
    }

    return false;
}


public function isAdministrator(): bool
{
    // Check if the user has a role with the name "admin"
    return $this->roles()->where('name', 'admin')->exists();
}



}
