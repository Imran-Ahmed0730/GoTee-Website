<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('clients', 'name')->ignore($this->route('client'))],
            'email' => ['required_without:phone', 'nullable', 'email', 'max:255', Rule::unique('clients', 'email')->ignore($this->route('client'))],
            'phone' => ['required_without:email', 'nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }
}
