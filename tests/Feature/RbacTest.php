<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users can have multiple roles and inherit permissions through them', function () {
    $reader = Role::query()->create(['name' => 'Reader', 'slug' => 'reader']);
    $editor = Role::query()->create(['name' => 'Editor', 'slug' => 'editor']);
    $permission = Permission::query()->create([
        'name' => 'View users',
        'slug' => 'users.view',
        'module' => 'users',
    ]);
    $reader->permissions()->attach($permission);
    $user = User::factory()->create();

    $user->syncRoles([$reader->slug, $editor]);

    expect($user->hasRole('reader'))->toBeTrue()
        ->and($user->hasRole($editor))->toBeTrue()
        ->and($user->hasAnyRole(['missing', 'editor']))->toBeTrue()
        ->and($user->hasPermission('users.view'))->toBeTrue()
        ->and($user->hasAnyPermission(['missing.permission', 'users.view']))->toBeTrue();

    $user->syncRoles([$editor->id]);

    expect($user->hasRole('reader'))->toBeFalse()
        ->and($user->roles)->toHaveCount(1)
        ->and($user->hasPermission('users.view'))->toBeFalse();
});

test('a verified authenticated user without roles can load the post-login dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard')
        ->assertSee(route('dashboard'));
});

test('only users with the role management permission can manage roles', function () {
    $permission = Permission::query()->create([
        'name' => 'Manage roles',
        'slug' => 'roles.manage',
        'module' => 'roles',
    ]);
    $managerRole = Role::query()->create(['name' => 'Role manager', 'slug' => 'role-manager']);
    $managerRole->permissions()->attach($permission);
    $manager = User::factory()->create();

    $this->actingAs($manager)->get(route('admin.roles.index'))->assertForbidden();

    $manager->syncRoles([$managerRole]);
    $this->actingAs($manager)->get(route('admin.roles.index'))->assertOk();
    $this->get(route('admin.roles.create'))->assertOk();

    $response = $this->post(route('admin.roles.store'), [
        'name' => 'Content editor',
        'slug' => 'content-editor',
        'description' => 'Edits site content',
        'permissions' => [$permission->id],
    ]);

    $response->assertRedirect(route('admin.roles.index'));
    $role = Role::query()->where('slug', 'content-editor')->firstOrFail();
    $this->assertModelExists($role);
    $this->assertDatabaseHas('role_permissions', [
        'role_id' => $role->id,
        'permission_id' => $permission->id,
    ]);
    $this->get(route('admin.roles.edit', $role))->assertOk();

    $this->put(route('admin.roles.update', $role), [
        'name' => 'Senior editor',
        'slug' => 'senior-editor',
    ])->assertRedirect(route('admin.roles.index'));

    $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'Senior editor']);
    $this->assertDatabaseMissing('role_permissions', [
        'role_id' => $role->id,
        'permission_id' => $permission->id,
    ]);

    $this->delete(route('admin.roles.destroy', $role))
        ->assertRedirect(route('admin.roles.index'));

    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});

test('view permissions allow read-only access to role and permission directories', function () {
    $roleView = Permission::query()->create([
        'name' => 'View roles',
        'slug' => 'roles.view',
        'module' => 'roles',
    ]);
    $permissionView = Permission::query()->create([
        'name' => 'View permissions',
        'slug' => 'permissions.view',
        'module' => 'permissions',
    ]);
    $viewerRole = Role::query()->create(['name' => 'Access viewer', 'slug' => 'access-viewer']);
    $viewerRole->permissions()->attach([$roleView->id, $permissionView->id]);
    $viewer = User::factory()->create();
    $viewer->syncRoles([$viewerRole]);

    $roleDirectory = $this->actingAs($viewer)->get(route('admin.roles.index'));
    $roleDirectory->assertOk()
        ->assertSee('Access control')
        ->assertSee(route('admin.roles.index'))
        ->assertSee(route('admin.permissions.index'))
        ->assertDontSee('Create role')
        ->assertDontSee('Create permission');
    $this->get(route('admin.roles.create'))->assertForbidden();
    $this->get(route('admin.permissions.index'))->assertOk();
    $this->get(route('admin.permissions.create'))->assertForbidden();
});

test('permission slugs must belong to their declared module', function () {
    $permission = Permission::query()->create([
        'name' => 'Manage permissions',
        'slug' => 'permissions.manage',
        'module' => 'permissions',
    ]);
    $managerRole = Role::query()->create(['name' => 'Permission manager', 'slug' => 'permission-manager']);
    $managerRole->permissions()->attach($permission);
    $manager = User::factory()->create();
    $manager->syncRoles([$managerRole]);

    $this->actingAs($manager)
        ->get(route('admin.permissions.index'))
        ->assertOk();
    $this->get(route('admin.permissions.create'))->assertOk();

    $this->actingAs($manager)
        ->from(route('admin.permissions.create'))
        ->post(route('admin.permissions.store'), [
            'name' => 'View users',
            'slug' => 'roles.view',
            'module' => 'users',
        ])
        ->assertSessionHasErrors('slug');

    $this->assertDatabaseMissing('permissions', ['slug' => 'roles.view']);

    $this->post(route('admin.permissions.store'), [
        'name' => 'View users',
        'slug' => 'users.view',
        'module' => 'users',
        'description' => 'View user records.',
    ])->assertRedirect(route('admin.permissions.index'));

    $createdPermission = Permission::query()->where('slug', 'users.view')->firstOrFail();
    $this->assertModelExists($createdPermission);
    $this->get(route('admin.permissions.edit', $createdPermission))->assertOk();

    $this->put(route('admin.permissions.update', $createdPermission), [
        'name' => 'Browse users',
        'slug' => 'users.browse',
        'module' => 'users',
    ])->assertRedirect(route('admin.permissions.index'));

    $this->assertDatabaseHas('permissions', [
        'id' => $createdPermission->id,
        'name' => 'Browse users',
        'slug' => 'users.browse',
    ]);

    $this->delete(route('admin.permissions.destroy', $createdPermission))
        ->assertRedirect(route('admin.permissions.index'));

    $this->assertDatabaseMissing('permissions', ['id' => $createdPermission->id]);
});

test('users with user management access can assign roles but cannot grant super-admin', function () {
    $manageUsers = Permission::query()->create([
        'name' => 'Manage users',
        'slug' => 'users.manage',
        'module' => 'users',
    ]);
    $managerRole = Role::query()->create(['name' => 'User manager', 'slug' => 'user-manager']);
    $managerRole->permissions()->attach($manageUsers);
    $viewer = Role::query()->create(['name' => 'Viewer', 'slug' => 'viewer']);
    $superAdmin = Role::query()->create(['name' => 'Super Admin', 'slug' => 'super-admin']);
    $manager = User::factory()->create();
    $target = User::factory()->create();
    $manager->syncRoles([$managerRole]);

    $this->actingAs($manager)->get(route('admin.access.users.index'))->assertOk();

    $this->actingAs($manager)
        ->put(route('admin.access.users.update', $target), ['roles' => [$viewer->id]])
        ->assertRedirect(route('admin.access.users.index'));

    $this->assertDatabaseHas('user_roles', ['user_id' => $target->id, 'role_id' => $viewer->id]);

    $this->put(route('admin.access.users.update', $target), ['roles' => [$superAdmin->id]])
        ->assertForbidden();

    $this->assertDatabaseMissing('user_roles', ['user_id' => $target->id, 'role_id' => $superAdmin->id]);
});

test('the super-admin role cannot be edited or deleted through the admin UI', function () {
    $manageRoles = Permission::query()->create([
        'name' => 'Manage roles',
        'slug' => 'roles.manage',
        'module' => 'roles',
    ]);
    $managerRole = Role::query()->create(['name' => 'Role manager', 'slug' => 'role-manager']);
    $managerRole->permissions()->attach($manageRoles);
    $superAdmin = Role::query()->create(['name' => 'Super Admin', 'slug' => 'super-admin']);
    $manager = User::factory()->create();
    $manager->syncRoles([$managerRole]);

    $this->actingAs($manager)->get(route('admin.roles.edit', $superAdmin))->assertForbidden();

    $this->actingAs($manager)
        ->put(route('admin.roles.update', $superAdmin), [
            'name' => 'Changed',
            'slug' => 'changed',
        ])
        ->assertForbidden();

    $this->delete(route('admin.roles.destroy', $superAdmin))->assertForbidden();

    $this->assertModelExists($superAdmin);
});

test('the last super-admin cannot remove their own administrator access', function () {
    $superAdmin = Role::query()->create(['name' => 'Super Admin', 'slug' => 'super-admin']);
    $user = User::factory()->create();
    $user->syncRoles([$superAdmin]);

    $this->actingAs($user)
        ->from(route('admin.access.users.index'))
        ->put(route('admin.access.users.update', $user), ['roles' => []])
        ->assertSessionHasErrors('roles');

    $this->assertDatabaseHas('user_roles', [
        'user_id' => $user->id,
        'role_id' => $superAdmin->id,
    ]);
});

test('database seeding gives the initial admin the protected super-admin role', function () {
    $this->seed();

    $initialAdmin = User::query()->where('email', 'test@example.com')->firstOrFail();

    expect($initialAdmin->hasRole('super-admin'))->toBeTrue()
        ->and($initialAdmin->hasPermission('permissions.manage'))->toBeTrue();
});
