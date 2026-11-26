<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserRoleController extends Controller
{
    public function index(Request $request): View
    {
        $isSuperAdmin = $request->user()->hasRole('super-admin');

        return view('admin.users.index', [
            'users' => User::query()->with('roles')->orderBy('name')->paginate(15),
            'roles' => Role::query()
                ->when(! $isSuperAdmin, fn ($query) => $query->where('slug', '!=', 'super-admin'))
                ->orderBy('name')
                ->get(),
            'isSuperAdmin' => $isSuperAdmin,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $actorIsSuperAdmin = $request->user()->hasRole('super-admin');
        $request->merge([
            'roles' => array_values(array_filter(
                (array) $request->input('roles', []),
                fn ($roleId) => $roleId !== '',
            )),
        ]);

        $validated = $request->validate([
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['integer', 'distinct', 'exists:roles,id'],
        ]);
        $roleIds = array_map('intval', $validated['roles'] ?? []);
        $superAdminRole = Role::query()->where('slug', 'super-admin')->first();

        if (! $actorIsSuperAdmin && (
            $user->hasRole('super-admin')
            || ($superAdminRole !== null && in_array($superAdminRole->id, $roleIds))
        )) {
            abort(403);
        }

        DB::transaction(function () use ($user, $roleIds): void {
            $superAdminRole = Role::query()
                ->where('slug', 'super-admin')
                ->lockForUpdate()
                ->first();

            if (
                $superAdminRole !== null
                && $user->roles()->whereKey($superAdminRole->getKey())->exists()
                && ! in_array($superAdminRole->id, $roleIds)
                && $superAdminRole->users()->count() <= 1
            ) {
                throw ValidationException::withMessages([
                    'roles' => 'At least one super-admin account must remain assigned.',
                ]);
            }

            $user->syncRoles($roleIds);
        });

        return redirect()->route('admin.access.users.index')->with('status', 'User roles updated successfully.');
    }
}
