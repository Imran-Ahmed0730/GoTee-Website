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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee?->user_id),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'father_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'present_address' => ['required', 'string', 'max:5000'],
            'permanent_address' => ['required', 'string', 'max:5000'],
            'contacting_person_first_name' => ['required', 'string', 'max:255'],
            'contacting_person_last_name' => ['nullable', 'string', 'max:255'],
            'contacting_person_phone' => ['required', 'string', 'max:255'],
            'contacting_person_relation' => ['required', 'string', 'max:255'],
            'contacting_person_address' => ['nullable', 'string', 'max:5000'],
            'id_card_no' => ['nullable', 'string', 'max:255', Rule::unique('employees', 'id_card_no')->ignore($employee)],
            'note' => ['nullable', 'string', 'max:5000'],
            'gender' => ['required', 'integer', Rule::in([1, 2, 3])],
            'blood_group_id' => ['nullable', 'integer', Rule::exists('blood_groups', 'id')],
            'reporting_manager_id' => ['nullable', 'integer', Rule::exists('employees', 'user_id')],
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
