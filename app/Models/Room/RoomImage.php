<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'room_type_id',
    'disk',
    'path',
    'alt_text',
    'image_type',
    'is_featured',
    'sort_order',
])]
class RoomImage extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }
}
