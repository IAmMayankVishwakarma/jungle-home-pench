<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    public function hasRole(string|Role $role): bool
    {
        return $this->loadMissing('roles')->roles->contains(
            fn (Role $assignedRole): bool => $role instanceof Role
                ? $assignedRole->is($role)
                : in_array($role, [$assignedRole->slug, $assignedRole->name], true),
        );
    }

    /**
     * @param  array<int, string|Role>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermission(string|Permission $permission): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->loadMissing('roles.permissions')->roles->contains(
            fn (Role $role): bool => $role->permissions->contains(
                fn (Permission $assignedPermission): bool => $permission instanceof Permission
                    ? $assignedPermission->is($permission)
                    : in_array($permission, [$assignedPermission->slug, $assignedPermission->name], true),
            ),
        );
    }

    /**
     * @param  array<int, string|Permission>  $permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<int, int|string|Role>  $roles
     */
    public function syncRoles(array $roles): void
    {
        $roleIds = collect($roles)->map(function (int|string|Role $role): int {
            if ($role instanceof Role) {
                return (int) $role->getKey();
            }

            if (is_int($role)) {
                return (int) Role::query()->findOrFail($role)->getKey();
            }

            return (int) Role::query()
                ->where(fn ($query) => $query->where('slug', $role)->orWhere('name', $role))
                ->firstOrFail()
                ->getKey();
        })->unique()->all();

        $this->roles()->sync($roleIds);
        $this->unsetRelation('roles');
    }

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
}
