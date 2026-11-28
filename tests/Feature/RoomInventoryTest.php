<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Room\Amenity;
use App\Models\Room\Feature;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Services\AccommodationGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function roomInventoryAdmin(array $permissions = ['rooms.manage']): User
{
    $user = User::factory()->create();
    $role = Role::query()->create([
        'name' => 'Room Inventory Admin '.uniqid(),
        'slug' => 'room-inventory-admin-'.uniqid(),
    ]);

    foreach ($permissions as $slug) {
        $permission = Permission::query()->firstOrCreate(
            ['slug' => $slug],
            [
                'name' => ucwords(str_replace('.', ' ', $slug)),
                'module' => explode('.', $slug)[0],
            ],
        );
        $role->permissions()->attach($permission);
    }

    $user->syncRoles([$role]);

    return $user;
}

function roomTypePayload(array $overrides = []): array
{
    return array_replace([
        'name' => 'Deluxe Cottage',
        'code' => 'DEL',
        'short_description' => 'A comfortable forest cottage.',
        'description' => 'A spacious cottage for families.',
        'target_room_count' => 10,
        'base_price' => 5500,
        'currency' => 'INR',
        'room_size' => 420,
        'room_size_unit' => 'sq_ft',
        'bed_type' => 'King',
        'number_of_beds' => 1,
        'number_of_adults' => 2,
        'number_of_children' => 1,
        'maximum_occupancy' => 3,
        'extra_person_allowed' => true,
        'maximum_extra_persons' => 1,
        'extra_person_charge' => 500,
        'extra_bed_available' => true,
        'extra_bed_charge' => 750,
        'pet_friendly' => false,
        'room_view' => 'Forest',
        'floor_or_building' => 'Main',
        'is_active' => true,
    ], $overrides);
}

it('allows an authorized admin to create and edit room types', function () {
    $admin = roomInventoryAdmin();

    $this->actingAs($admin)
        ->post(route('admin.room-types.store'), roomTypePayload())
        ->assertRedirect();

    $roomType = RoomType::query()->where('code', 'DEL')->firstOrFail();
    expect($roomType->slug)->toBe('deluxe-cottage');

    $this->get(route('admin.room-types.show', $roomType))->assertOk();

    $this->put(route('admin.room-types.update', $roomType), [
        'name' => 'Premium Cottage',
        'target_room_count' => 12,
        'base_price' => 6000,
        'is_active' => false,
    ])->assertRedirect(route('admin.room-types.show', $roomType));

    $roomType->refresh();
    expect($roomType->name)->toBe('Premium Cottage')
        ->and($roomType->target_room_count)->toBe(12)
        ->and($roomType->is_active)->toBeFalse();

    $this->patch(route('admin.room-types.toggle-active', $roomType))
        ->assertRedirect();
    expect($roomType->refresh()->is_active)->toBeTrue();
});

it('validates required fields and rejects duplicate room type codes', function () {
    $admin = roomInventoryAdmin();
    RoomType::factory()->create(['code' => 'DEL']);

    $this->actingAs($admin)
        ->post(route('admin.room-types.store'), [
            'name' => '',
            'code' => 'DEL',
            'target_room_count' => -1,
            'base_price' => -20,
        ])
        ->assertSessionHasErrors([
            'name',
            'code',
            'target_room_count',
            'base_price',
            'currency',
            'number_of_beds',
            'maximum_occupancy',
        ]);
});

it('forbids unauthorized users from creating room types', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.room-types.store'), roomTypePayload())
        ->assertForbidden();
});

it('assigns amenities and features and rejects invalid ids', function () {
    $admin = roomInventoryAdmin();
    $amenity = Amenity::factory()->create();
    $feature = Feature::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.room-types.store'), roomTypePayload([
            'amenities' => [$amenity->id],
            'features' => [$feature->id],
        ]))
        ->assertRedirect();

    $roomType = RoomType::query()->firstOrFail();
    expect($roomType->amenities->modelKeys())->toBe([$amenity->id])
        ->and($roomType->features->modelKeys())->toBe([$feature->id]);

    $this->post(route('admin.room-types.store'), roomTypePayload([
        'code' => 'BAD',
        'amenities' => [999999],
        'features' => [999999],
    ]))->assertSessionHasErrors(['amenities.0', 'features.0']);
});

it('generates exactly the remaining rooms and is idempotent', function () {
    $admin = roomInventoryAdmin();
    $roomType = RoomType::factory()->create([
        'code' => 'DEL',
        'target_room_count' => 10,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.room-types.generate', $roomType))
        ->assertRedirect();

    expect(Room::query()->where('room_type_id', $roomType->id)->count())->toBe(10);

    $this->post(route('admin.room-types.generate', $roomType))->assertRedirect();
    expect(Room::query()->where('room_type_id', $roomType->id)->count())->toBe(10);
});

it('generates only additional rooms and preserves existing room codes', function () {
    $admin = roomInventoryAdmin();
    $roomType = RoomType::factory()->create([
        'code' => 'DEL',
        'target_room_count' => 10,
    ]);
    $service = app(AccommodationGeneratorService::class);
    $service->generate($roomType);

    $existingCodes = Room::query()->where('room_type_id', $roomType->id)
        ->orderBy('serial_number')
        ->pluck('room_code')
        ->all();

    $roomType->update(['target_room_count' => 12]);
    $service->generate($roomType);

    expect(Room::query()->where('room_type_id', $roomType->id)->count())->toBe(12);
    expect(Room::query()->where('room_type_id', $roomType->id)
        ->orderBy('serial_number')
        ->pluck('room_code')
        ->take(10)
        ->all())->toBe($existingCodes);
});

it('reducing target quantity does not delete physical rooms', function () {
    $admin = roomInventoryAdmin();
    $roomType = RoomType::factory()->create([
        'code' => 'DEL',
        'target_room_count' => 10,
    ]);
    app(AccommodationGeneratorService::class)->generate($roomType);

    $this->actingAs($admin)
        ->put(route('admin.room-types.update', $roomType), [
            'target_room_count' => 6,
        ])
        ->assertRedirect();

    expect(Room::query()->where('room_type_id', $roomType->id)->count())->toBe(10);
});

it('uploads and deletes room images safely', function () {
    Storage::fake('public');
    $admin = roomInventoryAdmin();
    $roomType = RoomType::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.room-types.images.store', $roomType), [
            'images' => [UploadedFile::fake()->createWithContent(
                'featured.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='),
            )->mimeType('image/png')],
            'featured' => true,
            'alt_text' => 'Forest cottage exterior',
        ])
        ->assertRedirect();

    $featured = $roomType->images()->where('is_featured', true)->firstOrFail();
    Storage::disk('public')->assertExists($featured->path);

    $this->delete(route('admin.room-types.images.destroy', [$roomType, $featured]))
        ->assertRedirect();

    Storage::disk('public')->assertMissing($featured->path);
    $this->assertDatabaseMissing('room_images', ['id' => $featured->id, 'deleted_at' => null]);
});

it('rejects invalid room image formats', function () {
    Storage::fake('public');
    $admin = roomInventoryAdmin();
    $roomType = RoomType::factory()->create();

    $this->actingAs($admin)
        ->post(route('admin.room-types.images.store', $roomType), [
            'images' => [UploadedFile::fake()->create('document.pdf', 100, 'application/pdf')],
        ])
        ->assertSessionHasErrors('images.0');

    $this->assertDatabaseCount('room_images', 0);
});

it('filters and updates physical room statuses', function () {
    $admin = roomInventoryAdmin(['rooms.manage', 'housekeeping.manage']);
    $roomType = RoomType::factory()->create();
    $room = Room::factory()->create([
        'room_type_id' => $roomType->id,
        'room_code' => 'DEL-001',
        'serial_number' => 1,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.accommodations.index', ['room_code' => 'DEL-001']))
        ->assertOk()
        ->assertSee('DEL-001');

    $this->patch(route('admin.accommodations.status.update', $room), [
        'operational_status' => 'maintenance',
        'housekeeping_status' => 'dirty',
    ])->assertRedirect();

    $room->refresh();
    expect($room->operational_status)->toBe('maintenance')
        ->and($room->housekeeping_status)->toBe('dirty')
        ->and($room->statusHistories()->count())->toBe(1);
});

it('creates active amenity and feature catalog entries', function () {
    $admin = roomInventoryAdmin();

    $this->actingAs($admin)
        ->post(route('admin.amenities.store'), [
            'name' => 'Free Wi-Fi',
            'icon' => 'bi-wifi',
            'is_active' => true,
        ])
        ->assertRedirect();

    $this->post(route('admin.features.store'), [
        'name' => 'Private Balcony',
        'icon' => 'bi-tree',
        'is_active' => true,
    ])->assertRedirect();

    $this->assertDatabaseHas('amenities', ['slug' => 'free-wi-fi'])
        ->assertDatabaseHas('features', ['slug' => 'private-balcony']);
});
