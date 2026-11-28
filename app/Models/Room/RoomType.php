<?php

namespace App\Models\Room;

use Database\Factories\RoomTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'code',
    'slug',
    'short_description',
    'description',
    'target_room_count',
    'base_price',
    'currency',
    'room_size',
    'room_size_unit',
    'bed_type',
    'number_of_beds',
    'number_of_adults',
    'number_of_children',
    'maximum_occupancy',
    'extra_person_allowed',
    'maximum_extra_persons',
    'extra_person_charge',
    'extra_bed_available',
    'extra_bed_charge',
    'pet_friendly',
    'room_view',
    'floor_or_building',
    'is_active',
])]
class RoomType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): RoomTypeFactory
    {
        return RoomTypeFactory::new();
    }

    protected function casts(): array
    {
        return [
            'target_room_count' => 'integer',
            'base_price' => 'decimal:2',
            'room_size' => 'decimal:2',
            'number_of_beds' => 'integer',
            'number_of_adults' => 'integer',
            'number_of_children' => 'integer',
            'maximum_occupancy' => 'integer',
            'extra_person_allowed' => 'boolean',
            'maximum_extra_persons' => 'integer',
            'extra_person_charge' => 'decimal:2',
            'extra_bed_available' => 'boolean',
            'extra_bed_charge' => 'decimal:2',
            'pet_friendly' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_type_amenity');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'room_type_feature');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order');
    }

    public function featuredImage(): HasOne
    {
        return $this->hasOne(RoomImage::class)
            ->where('is_featured', true)
            ->where('image_type', 'featured');
    }
}
