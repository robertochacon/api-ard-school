<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsignaturaRequest extends FormRequest
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
            'code' => 'required|string|max:255|unique:courses',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:teachers,id',
            'grade_level' => 'required|string|max:255',
            'schedule' => 'nullable|string|max:255',
            'room' => 'nullable|string|max:255',
            'max_students' => 'nullable|integer|min:1',
        ];
    }
}
