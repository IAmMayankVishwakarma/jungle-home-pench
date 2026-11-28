@extends('admin.layouts.admin')
@section('title', $roomType->name)
@section('active', 'rooms')

@section('content')
<div class="ad-head">
    <div><h1>{{ $roomType->name }}</h1><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.room-types.index') }}">Room Types</a></li><li class="breadcrumb-item active">{{ $roomType->code }}</li></ol></nav></div>
    <div class="d-flex gap-2">
        @can('manage rooms')
        <a class="btn-ad btn-ad--ghost" href="{{ route('admin.room-types.edit', $roomType) }}">Edit</a>
        <button class="btn-ad" type="button" data-bs-toggle="modal" data-bs-target="#generateRoomsModal">Generate Accommodation</button>
        @endcan
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card-ad mb-4">
            <div class="card-ad__h"><h2>Room Type Information</h2><span class="bd {{ $roomType->is_active ? 'bd--confirmed' : 'bd--cancelled' }}">{{ $roomType->is_active ? 'Active' : 'Inactive' }}</span></div>
            <div class="card-ad__b">
                <div class="row g-3">
                    <div class="col-md-4"><small>Code</small><strong class="d-block">{{ $roomType->code }}</strong></div>
                    <div class="col-md-4"><small>Base Price</small><strong class="d-block">{{ $roomType->currency }} {{ number_format((float) $roomType->base_price, 2) }}</strong></div>
                    <div class="col-md-4"><small>Maximum Occupancy</small><strong class="d-block">{{ $roomType->maximum_occupancy }}</strong></div>
                    <div class="col-md-4"><small>Adults / Children</small><strong class="d-block">{{ $roomType->number_of_adults }} / {{ $roomType->number_of_children }}</strong></div>
                    <div class="col-md-4"><small>Bed</small><strong class="d-block">{{ $roomType->number_of_beds }} × {{ $roomType->bed_type ?: 'Not specified' }}</strong></div>
                    <div class="col-md-4"><small>Size</small><strong class="d-block">{{ $roomType->room_size ? $roomType->room_size.' '.$roomType->room_size_unit : 'Not specified' }}</strong></div>
                </div>
                @if($roomType->description)<hr><p>{{ $roomType->description }}</p>@endif
            </div>
        </div>

        <div class="card-ad mb-4">
            <div class="card-ad__h"><h2>Inventory</h2></div>
            <div class="card-ad__b">
                <div class="row g-3">
                    <div class="col-md-4"><small>Target</small><strong class="d-block">{{ $roomType->target_room_count }}</strong></div>
                    <div class="col-md-4"><small>Generated</small><strong class="d-block">{{ $roomType->rooms_count }}</strong></div>
                    <div class="col-md-4"><small>Remaining</small><strong class="d-block">{{ max(0, $roomType->target_room_count - $roomType->rooms_count) }}</strong></div>
                </div>
            </div>
        </div>

        <div class="card-ad">
            <div class="card-ad__h"><h2>Generated Rooms</h2><a class="btn-ad btn-ad--sm btn-ad--ghost" href="{{ route('admin.accommodations.index', ['room_type_id' => $roomType->id]) }}">View all</a></div>
            <div class="card-ad__b table-responsive">
                <table class="table ad-table align-middle"><thead><tr><th>Room Code</th><th>Operational</th><th>Housekeeping</th><th>Created</th></tr></thead><tbody>
                @forelse($roomType->rooms as $room)<tr><td><strong>{{ $room->room_code }}</strong></td><td>{{ str_replace('_', ' ', ucfirst($room->operational_status)) }}</td><td>{{ str_replace('_', ' ', ucfirst($room->housekeeping_status)) }}</td><td>{{ $room->created_at->format('d M Y') }}</td></tr>@empty<tr><td colspan="4" class="text-center">No physical rooms generated.</td></tr>@endforelse
                </tbody></table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card-ad mb-4">
            <div class="card-ad__h"><h2>Featured Image</h2></div>
            <div class="card-ad__b">
                @if($roomType->featuredImage)
                    <img class="img-fluid rounded" src="{{ Storage::disk($roomType->featuredImage->disk)->url($roomType->featuredImage->path) }}" alt="{{ $roomType->featuredImage->alt_text ?: $roomType->name }}">
                @else
                    <p class="text-muted mb-0">No featured image uploaded.</p>
                @endif
            </div>
        </div>

        <div class="card-ad mb-4">
            <div class="card-ad__h"><h2>Amenities</h2></div>
            <div class="card-ad__b">@forelse($roomType->amenities as $amenity)<span class="d-inline-flex align-items-center me-2 mb-2">@if($amenity->icon)<img src="{{ asset($amenity->icon) }}" width="22" height="22" alt="" class="me-1">@endif{{ $amenity->name }}</span>@empty<span class="text-muted">No amenities selected.</span>@endforelse</div>
        </div>

        <div class="card-ad">
            <div class="card-ad__h"><h2>Features</h2></div>
            <div class="card-ad__b">@forelse($roomType->features as $feature)<span class="d-inline-flex align-items-center me-2 mb-2">@if($feature->icon)<img src="{{ asset($feature->icon) }}" width="22" height="22" alt="" class="me-1">@endif{{ $feature->name }}</span>@empty<span class="text-muted">No features selected.</span>@endforelse</div>
        </div>

        @can('manage rooms')
        <div class="card-ad mt-4">
            <div class="card-ad__h"><h2>Upload Images</h2></div>
            <div class="card-ad__b">
                <form method="POST" action="{{ route('admin.room-types.images.store', $roomType) }}" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control mb-2" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple required>
                    <input class="form-control mb-2" name="alt_text" placeholder="Alt text">
                    <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="featured" value="1" id="featuredImage"><label class="form-check-label" for="featuredImage">First upload is featured</label></div>
                    <button class="btn-ad btn-ad--sm" type="submit">Upload</button>
                </form>
                @foreach($roomType->images as $image)
                    <form method="POST" action="{{ route('admin.room-types.images.destroy', [$roomType, $image]) }}" class="d-flex align-items-center gap-2 mt-2">
                        @csrf @method('DELETE')
                        <span class="flex-grow-1">{{ $image->is_featured ? 'Featured: ' : '' }}{{ basename($image->path) }}</span>
                        <button class="ad-ico ad-ico--sm text-danger" type="submit" aria-label="Delete"><svg class="ic" width="15" height="15" viewBox="0 0 24 24"><path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13"/></svg></button>
                    </form>
                @endforeach
            </div>
        </div>
        @endcan
    </div>
</div>

@can('manage rooms')
<div class="modal fade" id="generateRoomsModal" tabindex="-1" aria-labelledby="generateRoomsModalLabel" aria-hidden="true">
    <div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('admin.room-types.generate', $roomType) }}">
        @csrf
        <div class="modal-header"><h2 class="modal-title fs-5" id="generateRoomsModalLabel">Generate Accommodation</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="modal-body"><dl class="row mb-0"><dt class="col-7">Room Type</dt><dd class="col-5">{{ $roomType->name }}</dd><dt class="col-7">Target Rooms</dt><dd class="col-5">{{ $roomType->target_room_count }}</dd><dt class="col-7">Already Generated</dt><dd class="col-5">{{ $roomType->rooms_count }}</dd><dt class="col-7">Remaining</dt><dd class="col-5">{{ max(0, $roomType->target_room_count - $roomType->rooms_count) }}</dd></dl></div>
        <div class="modal-footer"><button type="button" class="btn-ad btn-ad--ghost" data-bs-dismiss="modal">Cancel</button><button class="btn-ad" type="submit">Confirm &amp; Generate Rooms</button></div>
    </form></div></div>
</div>
@endcan
@endsection
