<?php

namespace App\Services;

use App\Models\Room\Room;
use App\Models\Room\RoomType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AccommodationGeneratorService
{
    public function generate(RoomType $roomType): int
    {
        try {
            return DB::transaction(function () use ($roomType): int {
                $lockedType = RoomType::query()
                    ->whereKey($roomType->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $generatedCount = Room::withTrashed()
                    ->where('room_type_id', $lockedType->getKey())
                    ->count();
                $remaining = max(0, $lockedType->target_room_count - $generatedCount);

                if ($remaining === 0) {
                    return 0;
                }

                $serialNumbers = Room::withTrashed()
                    ->where('room_type_id', $lockedType->getKey())
                    ->pluck('serial_number')
                    ->flip();

                $created = 0;

                for ($serial = 1; $created < $remaining; $serial++) {
                    if ($serialNumbers->has($serial)) {
                        continue;
                    }

                    $roomCode = $lockedType->code.'-'.str_pad((string) $serial, 3, '0', STR_PAD_LEFT);

                    while (Room::withTrashed()->where('room_code', $roomCode)->exists()) {
                        $serial++;
                        $roomCode = $lockedType->code.'-'.str_pad((string) $serial, 3, '0', STR_PAD_LEFT);
                    }

                    Room::query()->create([
                        'room_type_id' => $lockedType->getKey(),
                        'room_code' => $roomCode,
                        'serial_number' => $serial,
                        'operational_status' => 'active',
                        'housekeeping_status' => 'clean',
                        'floor_or_building' => $lockedType->floor_or_building,
                    ]);

                    $serialNumbers->put($serial, true);
                    $created++;
                }

                return $created;
            });
        } catch (QueryException $exception) {
            throw new RuntimeException(
                'Accommodation generation failed. No rooms were added.',
                previous: $exception,
            );
        }
    }
}
