<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'identification_number' => 'required|string|max:255|unique:users',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'nullable|numeric',
            'qualification' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'office_location' => 'nullable|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
            'emergency_contact_email' => 'nullable|string|email|max:255',
        ];
    }
}
