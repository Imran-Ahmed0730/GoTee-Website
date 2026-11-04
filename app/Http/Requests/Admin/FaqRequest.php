<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FaqRequest extends FormRequest
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
            'category_id' => ['required', 'integer', 'exists:faq_categories,id'],
            'question' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faqs', 'question')
                    ->where('category_id', $this->input('category_id'))
                    ->ignore($this->route('faq')),
            ],
            'answer' => ['required', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }
}
