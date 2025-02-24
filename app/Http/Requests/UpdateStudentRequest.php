<?php

namespace App\Http\Requests;

use App\Models\Tenant\Student;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    protected $student;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $this->student = Student::findOrFail($this->route('student_id'));

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->student->user_id,
            'roll_number' => 'required|string|unique:students,roll_number,'.$this->student->id,
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

    public function getStudentModel()
    {
        return $this->student;
    }
}
