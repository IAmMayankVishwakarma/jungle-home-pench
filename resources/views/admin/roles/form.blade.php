@extends('admin.layouts.admin')

@section('title', $role->exists ? 'Edit role' : 'Create role')
@section('active', 'roles')

@section('content')
    <div class="ad-head">
        <div>
            <h1>{{ $role->exists ? 'Edit role' : 'Create role' }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    <li class="breadcrumb-item active">{{ $role->exists ? 'Edit' : 'Create' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ $role->exists ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
        @csrf
        @if ($role->exists)
            @method('PUT')
        @endif
        <div class="card-ad">
            <div class="card-ad__h"><h2>Role details</h2></div>
            <div class="card-ad__b">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required maxlength="100">
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="slug">Slug</label>
                        <input class="form-control" id="slug" name="slug" value="{{ old('slug', $role->slug) }}" required maxlength="100">
                        @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2" maxlength="1000">{{ old('description', $role->description) }}</textarea>
                        @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-ad mt-4">
            <div class="card-ad__h"><h2>Permissions</h2><span>Select the access this role grants.</span></div>
            <div class="card-ad__b">
                @php($selectedPermissions = old('permissions', $role->exists ? $role->permissions->modelKeys() : []))
                <div class="row g-4">
                    @foreach ($permissions as $module => $modulePermissions)
                        <div class="col-12 col-md-6 col-xl-4">
                            <h3 class="h6 text-uppercase">{{ $module }}</h3>
                            @foreach ($modulePermissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" @checked(in_array($permission->id, $selectedPermissions))>
                                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                                        {{ $permission->name }} <small class="d-block text-muted">{{ $permission->slug }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @error('permissions')<div class="text-danger small mt-3">{{ $message }}</div>@enderror
                @error('permissions.*')<div class="text-danger small mt-3">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="btn-ad" type="submit">{{ $role->exists ? 'Save changes' : 'Create role' }}</button>
            <a class="btn-ad btn-ad--ghost" href="{{ route('admin.roles.index') }}">Cancel</a>
        </div>
    </form>
@endsection
