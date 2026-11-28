<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UploadRoomImageRequest;
use App\Models\Room\RoomImage;
use App\Models\Room\RoomType;
use App\Services\RoomImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomImageController extends Controller
{
    public function store(
        UploadRoomImageRequest $request,
        RoomType $roomType,
        RoomImageService $service,
    ): RedirectResponse {
        foreach ($request->file('images', []) as $index => $file) {
            $service->upload(
                $roomType,
                $file,
                $request->boolean('featured') && $index === 0,
                $request->input('alt_text'),
            );
        }

        return back()->with('status', 'Room images uploaded successfully.');
    }

    public function destroy(
        RoomType $roomType,
        RoomImage $image,
        RoomImageService $service,
    ): RedirectResponse {
        $service->delete($roomType, $image);

        return back()->with('status', 'Image deleted.');
    }

    public function reorder(
        Request $request,
        RoomType $roomType,
        RoomImageService $service,
    ): RedirectResponse {
        $validated = $request->validate([
            'image_ids' => ['required', 'array'],
            'image_ids.*' => ['integer', 'distinct', 'exists:room_images,id'],
        ]);

        $service->reorder($roomType, $validated['image_ids']);

        return back()->with('status', 'Image order updated.');
    }
}
