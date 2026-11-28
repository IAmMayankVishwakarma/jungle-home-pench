<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRoomStatusRequest;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Services\AccommodationGeneratorService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function index(Request $request): View
    {
        $rooms = Room::query()
            ->with('roomType.featuredImage')
            ->when($request->filled('room_type_id'), fn ($query) => $query->where(
                'room_type_id',
                $request->integer('room_type_id'),
            ))
            ->when($request->filled('room_code'), fn ($query) => $query->where(
                'room_code',
                'like',
                '%'.$request->string('room_code')->toString().'%',
            ))
            ->when($request->filled('operational_status'), fn ($query) => $query->where(
                'operational_status',
                $request->string('operational_status')->toString(),
            ))
            ->when($request->filled('housekeeping_status'), fn ($query) => $query->where(
                'housekeeping_status',
                $request->string('housekeeping_status')->toString(),
            ))
            ->when($request->filled('floor'), fn ($query) => $query->where(
                'floor_or_building',
                'like',
                '%'.$request->string('floor')->toString().'%',
            ))
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.accommodations.index', [
            'rooms' => $rooms,
            'roomTypes' => RoomType::query()->orderBy('name')->get(),
        ]);
    }

    public function generate(
        RoomType $roomType,
        AccommodationGeneratorService $service,
    ): RedirectResponse {
        $created = $service->generate($roomType);

        return back()->with(
            'status',
            $created === 0
                ? 'No additional rooms were required.'
                : "Generated {$created} accommodation room(s).",
        );
    }

    public function updateStatus(
        UpdateRoomStatusRequest $request,
        Room $room,
    ): RedirectResponse {
        $validated = $request->validated();
        $room->update($validated);
        $room->statusHistories()->create([
            'changed_by' => $request->user()->getKey(),
            'operational_status' => $validated['operational_status'] ?? null,
            'housekeeping_status' => $validated['housekeeping_status'] ?? null,
            'changed_at' => now(),
        ]);

        return back()->with('status', 'Room status updated.');
    }
}
