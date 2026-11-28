# Room Type and Accommodation Inventory

## Database schema

- `room_types` stores room type configuration, pricing, occupancy, policies, inventory target, and active status.
- `rooms` stores generated physical accommodations and operational/housekeeping status.
- `amenities` and `features` are normalized catalogs.
- `room_type_amenity` and `room_type_feature` are pivot tables.
- `room_images` stores image metadata and public disk paths, not image binaries.
- `room_status_histories` records status changes and the changing user.

The placeholder `room_categories` table is removed by the
`2026_11_28_104935_drop_unused_room_categories_table` migration.

## Relationships

- RoomType has many Rooms and Images and belongs to many Amenities and Features.
- Room belongs to one RoomType and has many status-history records.
- Amenity and Feature belong to many RoomTypes.

## Routes

- `/admin/room-types`
- `/admin/room-types/{roomType}`
- `/admin/accommodations`
- `/admin/room-catalog`
- `/admin/room-types/{roomType}/generate`
- `/admin/room-types/{roomType}/images`

All routes use the existing `auth`, `verified`, and RBAC middleware.

## Amenities and features

The default catalog is populated by `database/seeders/RoomCatalogSeeder.php`.
Run it with:

```sh
php artisan db:seed --class=RoomCatalogSeeder
```

Icon identifiers in the database are public asset paths. The seeder uses SVG
files under `public/assets/images/amenities/` and
`public/assets/images/features/`.

## Permissions

- `rooms.view` allows read access.
- `rooms.manage` allows room type, accommodation, catalog, generation, and image changes.
- `housekeeping.manage` allows housekeeping status updates.

The existing RBAC seeder already creates `rooms.view`, `rooms.manage`, and
`housekeeping.manage`; Super Admin and Admin receive all permissions.

## Room generation

`AccommodationGeneratorService` wraps generation in a database transaction,
locks the room type row with `lockForUpdate()`, counts existing records,
and creates only the remaining target quantity. Repeated requests are
idempotent. Room codes use `<ROOM-CODE>-<SERIAL>` and the database enforces
unique room codes and serial numbers per room type.

Reducing a room type target never deletes generated rooms.

## Images

Uploads use Laravel's public disk under:

`storage/app/public/room-types/{room-type-id}/`

Run `php artisan storage:link` for public URL access.

## Commands and tests

```sh
php artisan migrate
php artisan test --filter=RoomInventoryTest
php artisan test
```
