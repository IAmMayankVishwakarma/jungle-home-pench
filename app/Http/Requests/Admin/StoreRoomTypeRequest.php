<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreRoomTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('rooms.manage') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'code' => ['nullable', 'string', 'max:20', 'alpha_dash', Rule::unique('room_types', 'code')],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'target_room_count' => ['required', 'integer', 'min:0', 'max:10000'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'room_size' => ['nullable', 'numeric', 'min:0'],
            'room_size_unit' => ['nullable', 'string', 'in:sq_ft,sq_m'],
            'bed_type' => ['nullable', 'string', 'max:100'],
            'number_of_beds' => ['required', 'integer', 'min:1', 'max:20'],
            'number_of_adults' => ['required', 'integer', 'min:1', 'max:100'],
            'number_of_children' => ['required', 'integer', 'min:0', 'max:100'],
            'maximum_occupancy' => ['required', 'integer', 'min:1', 'max:200'],
            'extra_person_allowed' => ['required', 'boolean'],
            'maximum_extra_persons' => ['required', 'integer', 'min:0', 'max:100'],
            'extra_person_charge' => ['required', 'numeric', 'min:0'],
            'extra_bed_available' => ['required', 'boolean'],
            'extra_bed_charge' => ['required', 'numeric', 'min:0'],
            'pet_friendly' => ['required', 'boolean'],
            'room_view' => ['nullable', 'string', 'max:100'],
            'floor_or_building' => ['nullable', 'string', 'max:150'],
            'is_active' => ['required', 'boolean'],
            'amenities' => ['sometimes', 'array'],
            'amenities.*' => ['integer', 'distinct', 'exists:amenities,id'],
            'features' => ['sometimes', 'array'],
            'features.*' => ['integer', 'distinct', 'exists:features,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $adultCount = (int) $this->input('number_of_adults', 0);
            $childCount = (int) $this->input('number_of_children', 0);
            $maximumOccupancy = (int) $this->input('maximum_occupancy', 0);

            if ($maximumOccupancy > 0 && $adultCount + $childCount > $maximumOccupancy) {
                $validator->errors()->add(
                    'maximum_occupancy',
                    'Maximum occupancy must include the configured adult and child counts.',
                );
            }
        });
    }
}
