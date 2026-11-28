<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadRoomImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasPermission('rooms.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:20'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'featured' => ['sometimes', 'boolean'],
            'alt_text' => ['nullable', 'string', 'max:255'],
        ];
    }
}
