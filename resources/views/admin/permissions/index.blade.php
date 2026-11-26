@extends('admin.layouts.admin')

@section('title', 'Permissions')
@section('active', 'permissions')

@section('content')
    <div class="ad-head">
        <div>
            <h1>Permissions</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </nav>
        </div>
        @can('manage permissions')
            <a class="btn-ad" href="{{ route('admin.permissions.create') }}">Create permission</a>
        @endcan
    </div>

    <div class="card-ad">
        <div class="card-ad__h"><h2>Permission directory</h2><span>{{ $permissions->total() }} permissions</span></div>
        <div class="card-ad__b">
            <div class="table-responsive">
                <table class="table ad-table align-middle">
                    <thead><tr><th>Permission</th><th>Slug</th><th>Module</th><th>Roles</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                            <tr>
                                <td>
                                    <strong>{{ $permission->name }}</strong>
                                    @if ($permission->description)<small class="d-block">{{ $permission->description }}</small>@endif
                                </td>
                                <td><code>{{ $permission->slug }}</code></td>
                                <td>{{ $permission->module }}</td>
                                <td>{{ $permission->roles_count }}</td>
                                <td>
                                    <div class="acts justify-content-end">
                                        @can('manage permissions')
                                            <a class="btn-ad btn-ad--sm btn-ad--ghost" href="{{ route('admin.permissions.edit', $permission) }}">Edit</a>
                                            <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('Delete this permission from all roles?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-ad btn-ad--sm btn-ad--ghost text-danger" type="submit">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Read only</span>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">No permissions have been created.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="ad-pager">{{ $permissions->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
