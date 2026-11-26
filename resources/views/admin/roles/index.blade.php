@extends('admin.layouts.admin')

@section('title', 'Roles')
@section('active', 'roles')

@section('content')
    <div class="ad-head">
        <div>
            <h1>Roles</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </nav>
        </div>
        @can('manage roles')
            <a class="btn-ad" href="{{ route('admin.roles.create') }}">Create role</a>
        @endcan
    </div>

    <div class="card-ad">
        <div class="card-ad__h">
            <h2>Role directory</h2>
            <span>{{ $roles->total() }} roles</span>
        </div>
        <div class="card-ad__b">
            <div class="table-responsive">
                <table class="table ad-table align-middle">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Slug</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if ($role->description)
                                        <small class="d-block">{{ $role->description }}</small>
                                    @endif
                                    @if ($role->slug === 'super-admin')
                                        <span class="bd bd--gold">Protected</span>
                                    @endif
                                </td>
                                <td><code>{{ $role->slug }}</code></td>
                                <td>{{ $role->users_count }}</td>
                                <td>{{ $role->permissions_count }}</td>
                                <td>
                                    <div class="acts justify-content-end">
                                        @if ($role->slug !== 'super-admin' && auth()->user()->can('manage roles'))
                                            <a class="btn-ad btn-ad--sm btn-ad--ghost" href="{{ route('admin.roles.edit', $role) }}">Edit</a>
                                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Delete this role and remove it from assigned users?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-ad btn-ad--sm btn-ad--ghost text-danger" type="submit">Delete</button>
                                            </form>
                                        @elseif ($role->slug === 'super-admin')
                                            <span class="text-muted">System role</span>
                                        @else
                                            <span class="text-muted">Read only</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">No roles have been created.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="ad-pager">{{ $roles->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
