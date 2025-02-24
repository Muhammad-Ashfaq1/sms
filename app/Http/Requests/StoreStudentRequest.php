<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roll_number' => 'required|string|unique:students,roll_number',
            'date_of_birth' => 'required|date',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'roll_number.unique' => 'This roll number is already taken.',
            'parent_phone.max' => 'Phone number cannot exceed 20 characters.',
        ];
    }
}
