<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
        $employee = $this->route('employee');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee?->user_id),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:5000'],
            'gender' => ['required', 'integer', Rule::in([1, 2, 3])],
            'blood_group_id' => ['nullable', 'integer', Rule::exists('blood_groups', 'id')],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'designation_id' => ['nullable', 'integer', Rule::exists('designations', 'id')],
            'nid_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'nid_no')->ignore($employee),
            ],
            'nid_image' => [
                Rule::requiredIf($employee === null),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'profile_photo_path' => [
                Rule::requiredIf($employee === null),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'joining_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'status' => ['required', 'boolean'],
            'show_as_team' => ['required', 'boolean'],
        ];
    }
}
