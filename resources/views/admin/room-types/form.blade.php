@extends('admin.layouts.admin')
@section('title', $roomType->exists ? 'Edit Room Type' : 'Create Room Type')
@section('active', 'rooms')

@section('content')
<div class="ad-head">
    <div><h1>{{ $roomType->exists ? 'Edit Room Type' : 'Create Room Type' }}</h1></div>
    <a class="btn-ad btn-ad--ghost" href="{{ route('admin.room-types.index') }}">Back</a>
</div>

@if($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ $roomType->exists ? route('admin.room-types.update', $roomType) : route('admin.room-types.store') }}" class="card-ad">
    @csrf
    @if($roomType->exists) @method('PUT') @endif
    <div class="card-ad__b">
        <h2 class="h5 mb-3">Basic Information</h2>
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Room Type Name *</label><input class="form-control" name="name" value="{{ old('name', $roomType->name) }}" required></div>
            <div class="col-md-3"><label class="form-label">Room Type Code</label><input class="form-control" name="code" value="{{ old('code', $roomType->code) }}" placeholder="Auto-generated"></div>
            <div class="col-md-3"><label class="form-label">Target Room Count *</label><input class="form-control" type="number" min="0" name="target_room_count" value="{{ old('target_room_count', $roomType->target_room_count ?? 0) }}" required></div>
            <div class="col-md-6"><label class="form-label">Short Description</label><input class="form-control" name="short_description" value="{{ old('short_description', $roomType->short_description) }}"></div>
            <div class="col-md-6"><label class="form-label">Detailed Description</label><textarea class="form-control" name="description" rows="3">{{ old('description', $roomType->description) }}</textarea></div>
            <div class="col-md-3"><label class="form-label">Base Price *</label><input class="form-control" type="number" step="0.01" min="0" name="base_price" value="{{ old('base_price', $roomType->base_price ?? 0) }}" required></div>
            <div class="col-md-2"><label class="form-label">Currency *</label><input class="form-control" name="currency" maxlength="3" value="{{ old('currency', $roomType->currency ?? 'INR') }}" required></div>
            <div class="col-md-2"><label class="form-label">Room Size</label><input class="form-control" type="number" step="0.01" min="0" name="room_size" value="{{ old('room_size', $roomType->room_size) }}"></div>
            <div class="col-md-2"><label class="form-label">Size Unit</label><select class="form-select" name="room_size_unit"><option value="">Select</option><option value="sq_ft" @selected(old('room_size_unit', $roomType->room_size_unit) === 'sq_ft')>sq. ft.</option><option value="sq_m" @selected(old('room_size_unit', $roomType->room_size_unit) === 'sq_m')>sq. m.</option></select></div>
            <div class="col-md-3"><label class="form-label">Bed Type</label><input class="form-control" name="bed_type" value="{{ old('bed_type', $roomType->bed_type) }}"></div>
            <div class="col-md-2"><label class="form-label">Beds *</label><input class="form-control" type="number" min="1" name="number_of_beds" value="{{ old('number_of_beds', $roomType->number_of_beds ?? 1) }}" required></div>
            <div class="col-md-2"><label class="form-label">Adults *</label><input class="form-control" type="number" min="1" name="number_of_adults" value="{{ old('number_of_adults', $roomType->number_of_adults ?? 1) }}" required></div>
            <div class="col-md-2"><label class="form-label">Children *</label><input class="form-control" type="number" min="0" name="number_of_children" value="{{ old('number_of_children', $roomType->number_of_children ?? 0) }}" required></div>
            <div class="col-md-3"><label class="form-label">Maximum Occupancy *</label><input class="form-control" type="number" min="1" name="maximum_occupancy" value="{{ old('maximum_occupancy', $roomType->maximum_occupancy ?? 1) }}" required></div>
            <div class="col-md-3"><label class="form-label">Room View</label><input class="form-control" name="room_view" value="{{ old('room_view', $roomType->room_view) }}"></div>
            <div class="col-md-3"><label class="form-label">Floor / Building</label><input class="form-control" name="floor_or_building" value="{{ old('floor_or_building', $roomType->floor_or_building) }}"></div>
        </div>

        <hr>
        <h2 class="h5 mb-3">Rates and Policies</h2>
        <div class="row g-3">
            @foreach(['extra_person_allowed'=>['Extra Person Allowed',false],'extra_bed_available'=>['Extra Bed Available',false],'pet_friendly'=>['Pet Friendly',false],'is_active'=>['Active',true]] as $field=>$details)
            <div class="col-md-3"><label class="form-label d-block">{{ $details[0] }}</label><select class="form-select" name="{{ $field }}"><option value="1" @selected((bool)old($field, $roomType->{$field} ?? $details[1]))>Yes</option><option value="0" @selected(!(bool)old($field, $roomType->{$field} ?? $details[1]))>No</option></select></div>
            @endforeach
            <div class="col-md-3"><label class="form-label">Maximum Extra Persons *</label><input class="form-control" type="number" min="0" name="maximum_extra_persons" value="{{ old('maximum_extra_persons', $roomType->maximum_extra_persons ?? 0) }}" required></div>
            <div class="col-md-3"><label class="form-label">Extra Person Charge *</label><input class="form-control" type="number" step="0.01" min="0" name="extra_person_charge" value="{{ old('extra_person_charge', $roomType->extra_person_charge ?? 0) }}" required></div>
            <div class="col-md-3"><label class="form-label">Extra Bed Charge *</label><input class="form-control" type="number" step="0.01" min="0" name="extra_bed_charge" value="{{ old('extra_bed_charge', $roomType->extra_bed_charge ?? 0) }}" required></div>
        </div>

        <hr>
        <h2 class="h5 mb-3">Amenities and Features</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Amenities</label>
                <input class="form-control mb-2" id="amenitySearch" placeholder="Search amenities">
                <div class="row g-2">
                    @foreach($amenities as $amenity)
                    <div class="col-sm-6 amenity-option">
                        <label class="card-ad d-block p-2 mb-0 amenity-option__label">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" @checked(in_array($amenity->id, old('amenities', $roomType->amenities->pluck('id')->all())))>
                            @if($amenity->icon)<img src="{{ asset($amenity->icon) }}" width="24" height="24" alt="" class="me-2">@endif
                            <span>{{ $amenity->name }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Features</label>
                <input class="form-control mb-2" id="featureSearch" placeholder="Search features">
                <div class="row g-2">
                    @foreach($features as $feature)
                    <div class="col-sm-6 feature-option">
                        <label class="card-ad d-block p-2 mb-0 feature-option__label">
                            <input type="checkbox" name="features[]" value="{{ $feature->id }}" @checked(in_array($feature->id, old('features', $roomType->features->pluck('id')->all())))>
                            @if($feature->icon)<img src="{{ asset($feature->icon) }}" width="24" height="24" alt="" class="me-2">@endif
                            <span>{{ $feature->name }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="card-ad__f text-end"><button class="btn-ad" type="submit">{{ $roomType->exists ? 'Update Room Type' : 'Create Room Type' }}</button></div>
</form>
@push('scripts')
<script>
document.querySelectorAll('#amenitySearch, #featureSearch').forEach(function(search) {
    search.addEventListener('input', function() {
        var selector = search.id === 'amenitySearch' ? '.amenity-option' : '.feature-option';
        document.querySelectorAll(selector).forEach(function(option) {
            option.hidden = !option.textContent.toLowerCase().includes(search.value.toLowerCase());
        });
    });
});
</script>
@endpush
@endsection
