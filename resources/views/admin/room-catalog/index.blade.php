@extends('admin.layouts.admin')
@section('title', 'Amenities and Features')
@section('active', 'rooms')

@section('content')
<div class="ad-head"><div><h1>Amenities &amp; Features</h1></div></div>
@can('manage rooms')
<div class="row g-4 mb-4">
    <div class="col-md-6"><div class="card-ad"><div class="card-ad__h"><h2>New Amenity</h2></div><div class="card-ad__b"><form method="POST" action="{{ route('admin.amenities.store') }}" class="row g-2">@csrf<input type="hidden" name="is_active" value="1"><div class="col-md-5"><input class="form-control" name="name" placeholder="Name" required></div><div class="col-md-5"><input class="form-control" name="icon" placeholder="Icon identifier"></div><div class="col-md-2"><button class="btn-ad w-100" type="submit">Add</button></div></form></div></div></div>
    <div class="col-md-6"><div class="card-ad"><div class="card-ad__h"><h2>New Feature</h2></div><div class="card-ad__b"><form method="POST" action="{{ route('admin.features.store') }}" class="row g-2">@csrf<input type="hidden" name="is_active" value="1"><div class="col-md-5"><input class="form-control" name="name" placeholder="Name" required></div><div class="col-md-5"><input class="form-control" name="icon" placeholder="Icon identifier"></div><div class="col-md-2"><button class="btn-ad w-100" type="submit">Add</button></div></form></div></div></div>
</div>
@endcan
<div class="row g-4">
    <div class="col-md-6"><div class="card-ad"><div class="card-ad__h"><h2>Amenities</h2></div><div class="card-ad__b table-responsive"><table class="table ad-table"><thead><tr><th>Name</th><th>Icon</th><th>Status</th><th></th></tr></thead><tbody>@foreach($amenities as $amenity)<tr><td><div class="d-flex align-items-center gap-2">@if($amenity->icon)<img src="{{ asset($amenity->icon) }}" width="24" height="24" alt="">@endif<strong>{{ $amenity->name }}</strong></div></td><td>{{ $amenity->icon ?: '—' }}</td><td>{{ $amenity->is_active ? 'Active' : 'Inactive' }}</td><td>@can('manage rooms')<form method="POST" action="{{ route('admin.amenities.destroy',$amenity) }}">@csrf @method('DELETE')<button class="ad-ico ad-ico--sm text-danger" type="submit">×</button></form>@endcan</td></tr>@endforeach</tbody></table></div></div></div>
    <div class="col-md-6"><div class="card-ad"><div class="card-ad__h"><h2>Features</h2></div><div class="card-ad__b table-responsive"><table class="table ad-table"><thead><tr><th>Name</th><th>Icon</th><th>Status</th><th></th></tr></thead><tbody>@foreach($features as $feature)<tr><td><div class="d-flex align-items-center gap-2">@if($feature->icon)<img src="{{ asset($feature->icon) }}" width="24" height="24" alt="">@endif<strong>{{ $feature->name }}</strong></div></td><td>{{ $feature->icon ?: '—' }}</td><td>{{ $feature->is_active ? 'Active' : 'Inactive' }}</td><td>@can('manage rooms')<form method="POST" action="{{ route('admin.features.destroy',$feature) }}">@csrf @method('DELETE')<button class="ad-ico ad-ico--sm text-danger" type="submit">×</button></form>@endcan</td></tr>@endforeach</tbody></table></div></div></div>
</div>
@endsection
