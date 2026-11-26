<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    public function index(): View
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::query()
                ->withCount('roles')
                ->orderBy('module')
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.permissions.form', ['permission' => new Permission]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedAttributes($request);
        Permission::query()->create($validated);

        return redirect()->route('admin.permissions.index')->with('status', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.form', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $permission->update($this->validatedAttributes($request, $permission));

        return redirect()->route('admin.permissions.index')->with('status', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('status', 'Permission deleted successfully.');
    }

    /**
     * @return array{name: string, slug: string, module: string, description: ?string}
     */
    private function validatedAttributes(Request $request, ?Permission $permission = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:150',
                'regex:/\A[a-z0-9]+(?:[._-][a-z0-9]+)*\z/',
                Rule::unique('permissions', 'slug')->ignore($permission),
            ],
            'module' => ['required', 'string', 'max:80', 'regex:/\A[a-z0-9]+(?:[-_][a-z0-9]+)*\z/'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if (! str_starts_with($validated['slug'], $validated['module'].'.')) {
            throw ValidationException::withMessages([
                'slug' => 'The permission slug must begin with its module followed by a dot.',
            ]);
        }

        return [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'module' => $validated['module'],
            'description' => $validated['description'] ?? null,
        ];
    }
}
