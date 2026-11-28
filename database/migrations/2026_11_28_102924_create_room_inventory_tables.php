<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('target_room_count')->default(0);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->decimal('room_size', 8, 2)->nullable();
            $table->string('room_size_unit', 10)->nullable();
            $table->string('bed_type')->nullable();
            $table->unsignedTinyInteger('number_of_beds')->default(1);
            $table->unsignedTinyInteger('number_of_adults')->default(1);
            $table->unsignedTinyInteger('number_of_children')->default(0);
            $table->unsignedTinyInteger('maximum_occupancy')->default(1);
            $table->boolean('extra_person_allowed')->default(false);
            $table->unsignedTinyInteger('maximum_extra_persons')->default(0);
            $table->decimal('extra_person_charge', 10, 2)->default(0);
            $table->boolean('extra_bed_available')->default(false);
            $table->decimal('extra_bed_charge', 10, 2)->default(0);
            $table->boolean('pet_friendly')->default(false);
            $table->string('room_view')->nullable();
            $table->string('floor_or_building')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('room_type_amenity', function (Blueprint $table) {
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->primary(['room_type_id', 'amenity_id']);
            $table->engine = 'InnoDB';
        });

        Schema::create('room_type_feature', function (Blueprint $table) {
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->primary(['room_type_id', 'feature_id']);
            $table->engine = 'InnoDB';
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('room_code', 40)->unique();
            $table->unsignedInteger('serial_number');
            $table->string('operational_status', 20)->default('active')->index();
            $table->string('housekeeping_status', 20)->default('clean')->index();
            $table->string('floor_or_building')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['room_type_id', 'serial_number']);
            $table->index(['room_type_id', 'operational_status']);
        });

        Schema::create('room_images', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('disk', 30)->default('public');
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->string('image_type', 20)->default('gallery');
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['room_type_id', 'sort_order']);
        });

        Schema::create('room_status_histories', function (Blueprint $table) {
            $table->id();
            $table->engine = 'InnoDB';
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('operational_status', 20)->nullable();
            $table->string('housekeeping_status', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
            $table->index(['room_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_status_histories');
        Schema::dropIfExists('room_images');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_type_feature');
        Schema::dropIfExists('room_type_amenity');
        Schema::dropIfExists('features');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('room_types');
    }
};
