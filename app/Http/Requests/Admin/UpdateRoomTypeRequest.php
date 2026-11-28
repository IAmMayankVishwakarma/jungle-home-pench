<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRoomTypeRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'code' => [
                'sometimes',
                'nullable',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('room_types', 'code')->ignore($this->route('roomType')),
            ],
            'short_description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'description' => ['sometimes', 'nullable', 'string'],
            'target_room_count' => ['sometimes', 'required', 'integer', 'min:0', 'max:10000'],
            'base_price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'required', 'string', 'size:3'],
            'room_size' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'room_size_unit' => ['sometimes', 'nullable', 'string', 'in:sq_ft,sq_m'],
            'bed_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'number_of_beds' => ['sometimes', 'required', 'integer', 'min:1', 'max:20'],
            'number_of_adults' => ['sometimes', 'required', 'integer', 'min:1', 'max:100'],
            'number_of_children' => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],
            'maximum_occupancy' => ['sometimes', 'required', 'integer', 'min:1', 'max:200'],
            'extra_person_allowed' => ['sometimes', 'required', 'boolean'],
            'maximum_extra_persons' => ['sometimes', 'required', 'integer', 'min:0', 'max:100'],
            'extra_person_charge' => ['sometimes', 'required', 'numeric', 'min:0'],
            'extra_bed_available' => ['sometimes', 'required', 'boolean'],
            'extra_bed_charge' => ['sometimes', 'required', 'numeric', 'min:0'],
            'pet_friendly' => ['sometimes', 'required', 'boolean'],
            'room_view' => ['sometimes', 'nullable', 'string', 'max:100'],
            'floor_or_building' => ['sometimes', 'nullable', 'string', 'max:150'],
            'is_active' => ['sometimes', 'required', 'boolean'],
            'amenities' => ['sometimes', 'array'],
            'amenities.*' => ['integer', 'distinct', 'exists:amenities,id'],
            'features' => ['sometimes', 'array'],
            'features.*' => ['integer', 'distinct', 'exists:features,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $roomType = $this->route('roomType');
            $adultCount = (int) $this->input('number_of_adults', $roomType?->number_of_adults ?? 0);
            $childCount = (int) $this->input('number_of_children', $roomType?->number_of_children ?? 0);
            $maximumOccupancy = (int) $this->input('maximum_occupancy', $roomType?->maximum_occupancy ?? 0);

            if ($maximumOccupancy > 0 && $adultCount + $childCount > $maximumOccupancy) {
                $validator->errors()->add(
                    'maximum_occupancy',
                    'Maximum occupancy must include the configured adult and child counts.',
                );
            }
        });
    }
}
