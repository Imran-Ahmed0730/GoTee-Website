<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CaseStudyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['nullable', 'integer', Rule::exists('clients', 'id')],
            'testimonial_id' => ['nullable', 'integer', Rule::exists('testimonials', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'challenge' => ['required', 'string', 'max:10000'],
            'solution' => ['required', 'string', 'max:10000'],
            'results' => ['required', 'string', 'max:10000'],
            'status' => ['required', 'boolean'],
        ];
    }
}
