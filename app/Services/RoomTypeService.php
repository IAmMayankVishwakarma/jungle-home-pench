<?php

namespace App\Services;

use App\Models\Room\RoomType;
use Illuminate\Support\Str;

class RoomTypeService
{
    public function create(array $data): RoomType
    {
        $amenityIds = $data['amenities'] ?? [];
        $featureIds = $data['features'] ?? [];
        unset($data['amenities'], $data['features']);

        $data['code'] = $this->resolveCode($data['code'] ?? null, $data['name']);
        $data['slug'] = Str::slug($data['name']);

        $roomType = RoomType::query()->create($data);
        $roomType->amenities()->sync($amenityIds);
        $roomType->features()->sync($featureIds);

        return $roomType;
    }

    public function update(RoomType $roomType, array $data): RoomType
    {
        $amenityIds = array_key_exists('amenities', $data) ? $data['amenities'] : null;
        $featureIds = array_key_exists('features', $data) ? $data['features'] : null;
        unset($data['amenities'], $data['features']);

        if (array_key_exists('name', $data)) {
            $data['slug'] = Str::slug($data['name']);
        }

        if (array_key_exists('code', $data) && empty($data['code'])) {
            $data['code'] = $roomType->code;
        }

        $roomType->update($data);

        if ($amenityIds !== null) {
            $roomType->amenities()->sync($amenityIds);
        }

        if ($featureIds !== null) {
            $roomType->features()->sync($featureIds);
        }

        return $roomType->refresh();
    }

    private function resolveCode(?string $code, string $name): string
    {
        if ($code !== null) {
            return Str::upper($code);
        }

        $letters = preg_replace('/[^A-Za-z0-9]/', '', $name);
        $base = Str::upper(Str::substr($letters ?: 'ROOM', 0, 3));
        $candidate = $base;
        $suffix = 2;

        while (RoomType::withTrashed()->where('code', $candidate)->exists()) {
            $candidate = $base.$suffix;
            $suffix++;
        }

        return $candidate;
    }
}
