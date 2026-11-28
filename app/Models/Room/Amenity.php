<?php

namespace App\Models\Room;

use Database\Factories\AmenityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'icon', 'is_active'])]
class Amenity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): AmenityFactory
    {
        return AmenityFactory::new();
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'room_type_amenity');
    }
}
