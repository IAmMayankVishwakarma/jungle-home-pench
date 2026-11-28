<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('rooms.manage')
            || $this->user()?->hasPermission('housekeeping.manage')
            || false;
    }

    public function rules(): array
    {
        return [
            'operational_status' => [
                'sometimes',
                'required',
                Rule::in(['active', 'inactive', 'maintenance', 'out_of_service']),
            ],
            'housekeeping_status' => [
                'sometimes',
                'required',
                Rule::in(['clean', 'dirty', 'cleaning_in_progress', 'inspected']),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if (! $this->has('operational_status') && ! $this->has('housekeeping_status')) {
                $validator->errors()->add('status', 'Provide an operational or housekeeping status.');
            }
        });
    }
}
