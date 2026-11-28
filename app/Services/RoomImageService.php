<?php

namespace App\Services;

use App\Models\Room\RoomImage;
use App\Models\Room\RoomType;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomImageService
{
    public function upload(
        RoomType $roomType,
        UploadedFile $file,
        bool $featured,
        ?string $altText = null,
    ): RoomImage {
        return DB::transaction(function () use ($roomType, $file, $featured, $altText): RoomImage {
            if ($featured) {
                RoomImage::query()
                    ->where('room_type_id', $roomType->getKey())
                    ->update([
                        'is_featured' => false,
                        'image_type' => 'gallery',
                    ]);
            }

            $path = $file->store("room-types/{$roomType->getKey()}", 'public');
            $sortOrder = RoomImage::query()
                ->where('room_type_id', $roomType->getKey())
                ->max('sort_order');

            return RoomImage::query()->create([
                'room_type_id' => $roomType->getKey(),
                'disk' => 'public',
                'path' => $path,
                'alt_text' => $altText,
                'image_type' => $featured ? 'featured' : 'gallery',
                'is_featured' => $featured,
                'sort_order' => ((int) $sortOrder) + 1,
            ]);
        });
    }

    public function delete(RoomType $roomType, RoomImage $image): void
    {
        if ($image->room_type_id !== $roomType->getKey()) {
            throw (new ModelNotFoundException())->setModel(RoomImage::class);
        }

        DB::transaction(function () use ($image): void {
            Storage::disk($image->disk)->delete($image->path);
            $image->delete();
        });
    }

    public function reorder(RoomType $roomType, array $imageIds): void
    {
        DB::transaction(function () use ($roomType, $imageIds): void {
            foreach ($imageIds as $index => $imageId) {
                RoomImage::query()
                    ->whereKey((int) $imageId)
                    ->where('room_type_id', $roomType->getKey())
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }
}
