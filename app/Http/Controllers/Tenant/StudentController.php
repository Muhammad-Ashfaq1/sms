<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->get();
        return view('tenant.students.index', compact('students'));
    }

    public function create()
    {
        return view('tenant.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roll_number' => 'required|string|unique:students,roll_number',
            'date_of_birth' => 'required|date',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'), // Default password
            ]);

            $user->assignRole('student');

            Student::create([
                'user_id' => $user->id,
                'roll_number' => $request->roll_number,
                'date_of_birth' => $request->date_of_birth,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'address' => $request->address,
                'status' => $request->status,
            ]);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'html' => view('tenant.students.table', ['students' => Student::with('user')->get()])->render()
            ]);
        }

        return redirect()->route('tenant.students.index')
            ->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $student->load('user');
        if (request()->ajax()) {
            return response()->json($student);
        }
        return view('tenant.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'roll_number' => 'required|string|unique:students,roll_number,'.$student->id,
            'date_of_birth' => 'required|date',
            'parent_name' => 'required|string',
            'parent_phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $student->update([
                'roll_number' => $request->roll_number,
                'date_of_birth' => $request->date_of_birth,
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_phone,
                'address' => $request->address,
                'status' => $request->status,
            ]);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'html' => view('tenant.students.table', ['students' => Student::with('user')->get()])->render()
            ]);
        }

        return redirect()->route('tenant.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->user->delete();
            $student->delete();
        });

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully',
                'html' => view('tenant.students.table', ['students' => Student::with('user')->get()])->render()
            ]);
        }

        return redirect()->route('tenant.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
