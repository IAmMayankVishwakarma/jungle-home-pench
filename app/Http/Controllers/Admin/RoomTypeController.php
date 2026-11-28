<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomTypeRequest;
use App\Http\Requests\Admin\UpdateRoomTypeRequest;
use App\Models\Room\Amenity;
use App\Models\Room\Feature;
use App\Models\Room\RoomType;
use App\Services\RoomTypeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Request $request): View
    {
        $roomTypes = RoomType::query()
            ->withCount('rooms')
            ->with('featuredImage')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'),
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create(): View
    {
        return view('admin.room-types.form', [
            'roomType' => new RoomType(),
            'amenities' => Amenity::query()->where('is_active', true)->orderBy('name')->get(),
            'features' => Feature::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreRoomTypeRequest $request, RoomTypeService $service): RedirectResponse
    {
        $roomType = $service->create($request->validated());

        return redirect()
            ->route('admin.room-types.show', $roomType)
            ->with('status', 'Room type created successfully.');
    }

    public function show(RoomType $roomType): View
    {
        $roomType->load([
            'amenities',
            'features',
            'images',
            'rooms' => fn ($query) => $query->latest()->limit(25),
        ]);
        $roomType->loadCount('rooms');

        return view('admin.room-types.show', compact('roomType'));
    }

    public function edit(RoomType $roomType): View
    {
        $roomType->load(['amenities', 'features']);

        return view('admin.room-types.form', [
            'roomType' => $roomType,
            'amenities' => Amenity::query()->where('is_active', true)->orderBy('name')->get(),
            'features' => Feature::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(
        UpdateRoomTypeRequest $request,
        RoomType $roomType,
        RoomTypeService $service,
    ): RedirectResponse {
        $service->update($roomType, $request->validated());

        return redirect()
            ->route('admin.room-types.show', $roomType)
            ->with('status', 'Room type updated successfully.');
    }

    public function destroy(RoomType $roomType): RedirectResponse
    {
        $roomType->delete();

        return redirect()
            ->route('admin.room-types.index')
            ->with('status', 'Room type deactivated and removed from the listing.');
    }

    public function toggleActive(RoomType $roomType): RedirectResponse
    {
        $roomType->update(['is_active' => ! $roomType->is_active]);

        return back()->with('status', 'Room type status updated.');
    }
}
