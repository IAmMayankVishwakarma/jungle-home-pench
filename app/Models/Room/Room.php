<?php

namespace App\Models\Room;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'room_type_id',
    'room_code',
    'serial_number',
    'operational_status',
    'housekeeping_status',
    'floor_or_building',
])]
class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): RoomFactory
    {
        return RoomFactory::new();
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(RoomStatusHistory::class)->latest('changed_at');
    }
}
