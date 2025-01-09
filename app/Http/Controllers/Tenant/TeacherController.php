<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Teacher;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();

        return view('tenant.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('tenant.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'), // Default password
            ]);

            // Assign teacher role
            $user->assignRole('teacher');

            // Create teacher profile
            Teacher::create([
                'user_id' => $user->id,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'joining_date' => $request->joining_date,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('tenant.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return view('tenant.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenant_users,email,'.$teacher->user_id,
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            // Update user
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update teacher profile
            $teacher->update([
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'joining_date' => $request->joining_date,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('tenant.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $teacher->user->delete(); // This will cascade delete the teacher record
        });

        return redirect()->route('tenant.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
