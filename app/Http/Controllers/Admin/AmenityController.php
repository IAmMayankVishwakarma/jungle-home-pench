<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAmenityRequest;
use App\Models\Room\Amenity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        return view('admin.room-catalog.index', [
            'amenities' => Amenity::query()->latest()->paginate(30),
            'features' => \App\Models\Room\Feature::query()->latest()->paginate(30),
        ]);
    }

    public function store(StoreAmenityRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        Amenity::query()->create($validated);

        return back()->with('status', 'Amenity created successfully.');
    }

    public function update(StoreAmenityRequest $request, Amenity $amenity): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $amenity->update($validated);

        return back()->with('status', 'Amenity updated successfully.');
    }

    public function destroy(Amenity $amenity): RedirectResponse
    {
        $amenity->delete();

        return back()->with('status', 'Amenity removed.');
    }
}
