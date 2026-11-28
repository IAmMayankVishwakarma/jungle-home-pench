<?php

namespace App\Models\Room;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'room_id',
    'changed_by',
    'operational_status',
    'housekeeping_status',
    'note',
    'changed_at',
])]
class RoomStatusHistory extends Model
{
    protected function casts(): array
    {
        return ['changed_at' => 'datetime'];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
