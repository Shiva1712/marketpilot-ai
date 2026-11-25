<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'industry' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'timezone' => 'nullable|timezone',
        ];

    }
}
