@extends('admin.layouts.admin')

@section('title', 'User access')
@section('active', 'users')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ad-head">
        <div>
            <h1>User access</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">User access</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card-ad">
        <div class="card-ad__h"><h2>Assign roles</h2><span>{{ $users->total() }} users</span></div>
        <div class="card-ad__b">
            <div class="table-responsive">
                <table class="table ad-table align-middle">
                    <thead><tr><th>User</th><th>Assigned roles</th><th>Update access</th></tr></thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php($isProtectedUser = $user->hasRole('super-admin') && ! $isSuperAdmin)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    <small class="d-block">{{ $user->email }}</small>
                                </td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <span class="bd {{ $role->slug === 'super-admin' ? 'bd--gold' : 'bd--active' }}">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">No roles</span>
                                    @endforelse
                                </td>
                                <td>
                                    @if ($isProtectedUser)
                                        <span class="text-muted">Protected super-admin account</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.access.users.update', $user) }}" class="d-flex flex-wrap align-items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="roles[]" value="">
                                            @foreach ($roles as $role)
                                                <label class="form-check form-check-inline mb-0">
                                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" @checked($user->roles->contains($role->id))>
                                                    <span class="form-check-label">{{ $role->name }}</span>
                                                </label>
                                            @endforeach
                                            <button class="btn-ad btn-ad--sm" type="submit">Save</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-4">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="ad-pager">{{ $users->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
