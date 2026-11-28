<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFeatureRequest;
use App\Models\Room\Feature;
use Illuminate\Http\RedirectResponse;

class FeatureController extends Controller
{
    public function store(StoreFeatureRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        Feature::query()->create($validated);

        return back()->with('status', 'Feature created successfully.');
    }

    public function update(StoreFeatureRequest $request, Feature $feature): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $feature->update($validated);

        return back()->with('status', 'Feature updated successfully.');
    }

    public function destroy(Feature $feature): RedirectResponse
    {
        $feature->delete();

        return back()->with('status', 'Feature removed.');
    }
}
