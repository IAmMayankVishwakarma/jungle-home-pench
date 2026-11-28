@extends('admin.layouts.admin')
@section('title', 'Physical Accommodations')
@section('active', 'rooms')

@section('content')
<div class="ad-head"><div><h1>Physical Accommodations</h1><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item active">Accommodations</li></ol></nav></div></div>
<div class="card-ad mb-4"><div class="card-ad__b"><form method="GET" class="row g-2">
    <div class="col-md-3"><select class="form-select" name="room_type_id"><option value="">All room types</option>@foreach($roomTypes as $roomType)<option value="{{ $roomType->id }}" @selected(request('room_type_id') == $roomType->id)>{{ $roomType->name }}</option>@endforeach</select></div>
    <div class="col-md-2"><input class="form-control" name="room_code" value="{{ request('room_code') }}" placeholder="Room code"></div>
    <div class="col-md-2"><select class="form-select" name="operational_status"><option value="">Operational</option>@foreach(['active','inactive','maintenance','out_of_service'] as $status)<option value="{{ $status }}" @selected(request('operational_status') === $status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select></div>
    <div class="col-md-2"><select class="form-select" name="housekeeping_status"><option value="">Housekeeping</option>@foreach(['clean','dirty','cleaning_in_progress','inspected'] as $status)<option value="{{ $status }}" @selected(request('housekeeping_status') === $status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select></div>
    <div class="col-md-2"><input class="form-control" name="floor" value="{{ request('floor') }}" placeholder="Floor"></div>
    <div class="col-md-1"><button class="btn-ad w-100" type="submit">Go</button></div>
</form></div></div>

<div class="card-ad"><div class="card-ad__b table-responsive"><table class="table ad-table align-middle"><thead><tr><th>Room</th><th>Room Type</th><th>Operational</th><th>Housekeeping</th><th>Floor</th><th>Generated</th><th></th></tr></thead><tbody>
@forelse($rooms as $room)
<tr>
    <td><strong>{{ $room->room_code }}</strong></td><td>{{ $room->roomType->name }}</td><td>{{ str_replace('_',' ',ucfirst($room->operational_status)) }}</td><td>{{ str_replace('_',' ',ucfirst($room->housekeeping_status)) }}</td><td>{{ $room->floor_or_building ?: '—' }}</td><td>{{ $room->created_at->format('d M Y') }}</td>
    <td>
    @canany(['manage rooms','manage housekeeping'])
    <form method="POST" action="{{ route('admin.accommodations.status.update', $room) }}" class="d-flex gap-2">@csrf @method('PATCH')
        <select class="form-select form-select-sm" name="operational_status">@foreach(['active','inactive','maintenance','out_of_service'] as $status)<option value="{{ $status }}" @selected($room->operational_status === $status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select>
        <select class="form-select form-select-sm" name="housekeeping_status">@foreach(['clean','dirty','cleaning_in_progress','inspected'] as $status)<option value="{{ $status }}" @selected($room->housekeeping_status === $status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>@endforeach</select>
        <button class="btn-ad btn-ad--sm" type="submit">Save</button>
    </form>
    @endcanany
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center py-4">No physical rooms found.</td></tr>@endforelse
</tbody></table>{{ $rooms->links() }}</div></div>
@endsection
