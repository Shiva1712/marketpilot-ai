<?php

namespace App\Http\Requests\Api\V1;
use Illuminate\Foundation\Http\FormRequest;

class BusinessProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Business membership and permissions are checked
        // in the controller/service or Laravel Policy.
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'target_audience' => [
                'nullable',
                'array',
                'max:500',
            ],

            'brand_voice' => [
                'nullable',
                'array',
                'max:100',
            ],

            'marketing_goals' => [
                'nullable',
                'array',
                'max:20',
            ],

            'marketing_goals.*' => [
                'required',
                'string',
                'max:255',
            ],

            'timezone' => [
                'nullable',
                'timezone',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'description.string' => 'Description must be a valid string.',
            'target_audience.string' => 'Target audience must be a valid string.',
            'brand_voice.string' => 'Brand voice must be a valid string.',
            'marketing_goals.array' => 'Marketing goals must be an array.',
            'marketing_goals.*.required' => 'Each marketing goal is required.',
            'marketing_goals.*.string' => 'Each marketing goal must be a string.',
            'timezone.timezone' => 'Please provide a valid timezone.',
        ];
    }

    /**
     * Validation failure response.
     */
    public function failedValidation(
        \Illuminate\Contracts\Validation\Validator $validator
    ): void {
        parent::failedValidation($validator);
    }
}
