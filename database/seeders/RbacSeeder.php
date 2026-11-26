<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $roles = collect([
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Unrestricted access to the administration area.'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Manage administration and operational areas.'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Manage operational and site content.'],
            ['name' => 'Viewer', 'slug' => 'viewer', 'description' => 'Read-only access to administration data.'],
        ])->mapWithKeys(function (array $attributes): array {
            $slug = $attributes['slug'];
            unset($attributes['slug']);

            return [$slug => Role::query()->updateOrCreate(['slug' => $slug], $attributes)];
        });

        $permissions = [
            ['name' => 'View dashboard', 'slug' => 'dashboard.view', 'module' => 'dashboard'],
            ['name' => 'View users', 'slug' => 'users.view', 'module' => 'users'],
            ['name' => 'Manage users and role assignments', 'slug' => 'users.manage', 'module' => 'users'],
            ['name' => 'View roles', 'slug' => 'roles.view', 'module' => 'roles'],
            ['name' => 'Manage roles', 'slug' => 'roles.manage', 'module' => 'roles'],
            ['name' => 'View permissions', 'slug' => 'permissions.view', 'module' => 'permissions'],
            ['name' => 'Manage permissions', 'slug' => 'permissions.manage', 'module' => 'permissions'],
            ['name' => 'View bookings', 'slug' => 'bookings.view', 'module' => 'bookings'],
            ['name' => 'Manage bookings', 'slug' => 'bookings.manage', 'module' => 'bookings'],
            ['name' => 'View rooms', 'slug' => 'rooms.view', 'module' => 'rooms'],
            ['name' => 'Manage rooms', 'slug' => 'rooms.manage', 'module' => 'rooms'],
            ['name' => 'View guests', 'slug' => 'guests.view', 'module' => 'guests'],
            ['name' => 'Manage guests', 'slug' => 'guests.manage', 'module' => 'guests'],
            ['name' => 'View payments', 'slug' => 'payments.view', 'module' => 'payments'],
            ['name' => 'Manage payments', 'slug' => 'payments.manage', 'module' => 'payments'],
            ['name' => 'Manage housekeeping', 'slug' => 'housekeeping.manage', 'module' => 'housekeeping'],
            ['name' => 'Manage services', 'slug' => 'services.manage', 'module' => 'services'],
            ['name' => 'Manage reviews', 'slug' => 'reviews.manage', 'module' => 'reviews'],
            ['name' => 'Manage blog', 'slug' => 'blog.manage', 'module' => 'blog'],
            ['name' => 'View reports', 'slug' => 'reports.view', 'module' => 'reports'],
            ['name' => 'Manage settings', 'slug' => 'settings.manage', 'module' => 'settings'],
        ];

        foreach ($permissions as $attributes) {
            Permission::query()->updateOrCreate(
                ['slug' => $attributes['slug']],
                $attributes + ['description' => null],
            );
        }

        $permissionIds = Permission::query()->pluck('id', 'slug');
        $roles['super-admin']->permissions()->sync($permissionIds->values());
        $roles['admin']->permissions()->sync($permissionIds->values());

        $roles['editor']->permissions()->sync(
            $permissionIds->only([
                'dashboard.view',
                'bookings.view',
                'bookings.manage',
                'rooms.view',
                'rooms.manage',
                'guests.view',
                'housekeeping.manage',
                'services.manage',
                'reviews.manage',
                'blog.manage',
                'reports.view',
            ])->values(),
        );

        $roles['viewer']->permissions()->sync(
            Permission::query()
                ->where('slug', 'like', '%.view')
                ->pluck('id'),
        );

        $adminEmail = config('rbac.initial_admin_email');
        $initialAdmin = User::query()->where('email', $adminEmail)->first();

        if ($initialAdmin === null) {
            throw new RuntimeException(
                "The initial RBAC admin [{$adminEmail}] was not found. Create that user or set RBAC_INITIAL_ADMIN_EMAIL.",
            );
        }

        $initialAdmin->syncRoles([$roles['super-admin']]);
    }
}
