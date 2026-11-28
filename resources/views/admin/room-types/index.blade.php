@extends('admin.layouts.admin')
@section('title', 'Room Types')
@section('active', 'rooms')

@section('content')
<div class="ad-head">
    <div>
        <h1>Room Types</h1>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Room Types</li></ol></nav>
    </div>
    @can('manage rooms')
    <a class="btn-ad" href="{{ route('admin.room-types.create') }}">Create room type</a>
    @endcan
</div>

<div class="card-ad mb-4">
    <div class="card-ad__b">
        <form method="GET" class="row g-3">
            <div class="col-md-6"><input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Search name or code"></div>
            <div class="col-md-3"><select class="form-select" name="status"><option value="">All statuses</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="inactive" @selected(request('status') === 'inactive')>Inactive</option></select></div>
            <div class="col-md-3"><button class="btn-ad" type="submit">Filter</button></div>
        </form>
    </div>
</div>

<div class="card-ad">
    <div class="card-ad__b table-responsive">
        <table class="table ad-table align-middle">
            <thead><tr><th>Room Type</th><th>Code</th><th>Price</th><th>Target / Generated</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($roomTypes as $roomType)
                <tr>
                    <td><strong>{{ $roomType->name }}</strong></td>
                    <td>{{ $roomType->code }}</td>
                    <td>{{ $roomType->currency }} {{ number_format((float) $roomType->base_price, 2) }}</td>
                    <td>{{ $roomType->target_room_count }} / {{ $roomType->rooms_count }}</td>
                    <td><span class="bd {{ $roomType->is_active ? 'bd--confirmed' : 'bd--cancelled' }}">{{ $roomType->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="text-end">
                        <a class="ad-ico ad-ico--sm" href="{{ route('admin.room-types.show', $roomType) }}" aria-label="View"><svg class="ic" width="15" height="15" viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        @can('manage rooms')
                        <a class="ad-ico ad-ico--sm" href="{{ route('admin.room-types.edit', $roomType) }}" aria-label="Edit"><svg class="ic" width="15" height="15" viewBox="0 0 24 24"><path d="M4 20h4L19 9l-4-4L4 16zM13 7l4 4"/></svg></a>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">No room types found.</td></tr>
            @endforelse
            </tbody>
        </table>
        {{ $roomTypes->links() }}
    </div>
</div>
@endsection
