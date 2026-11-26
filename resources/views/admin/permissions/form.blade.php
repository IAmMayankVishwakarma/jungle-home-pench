@extends('admin.layouts.admin')

@section('title', $permission->exists ? 'Edit permission' : 'Create permission')
@section('active', 'permissions')

@section('content')
    <div class="ad-head">
        <div>
            <h1>{{ $permission->exists ? 'Edit permission' : 'Create permission' }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                    <li class="breadcrumb-item active">{{ $permission->exists ? 'Edit' : 'Create' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form method="POST" action="{{ $permission->exists ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}">
        @csrf
        @if ($permission->exists)
            @method('PUT')
        @endif
        <div class="card-ad">
            <div class="card-ad__h"><h2>Permission details</h2></div>
            <div class="card-ad__b">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="name">Name</label>
                        <input class="form-control" id="name" name="name" value="{{ old('name', $permission->name) }}" required maxlength="100">
                        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="module">Module</label>
                        <input class="form-control" id="module" name="module" value="{{ old('module', $permission->module) }}" required maxlength="80" placeholder="users">
                        @error('module')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="slug">Slug</label>
                        <input class="form-control" id="slug" name="slug" value="{{ old('slug', $permission->slug) }}" required maxlength="150" placeholder="users.view">
                        <small class="text-muted">Use the module prefix, for example users.view.</small>
                        @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" maxlength="1000">{{ old('description', $permission->description) }}</textarea>
                        @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn-ad" type="submit">{{ $permission->exists ? 'Save changes' : 'Create permission' }}</button>
            <a class="btn-ad btn-ad--ghost" href="{{ route('admin.permissions.index') }}">Cancel</a>
        </div>
    </form>
@endsection
