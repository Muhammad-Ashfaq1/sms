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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
            ]);

            $user->assignRole('teacher');

            Teacher::create([
                'user_id' => $user->id,
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'joining_date' => $request->joining_date,
                'status' => $request->status,
            ]);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'html' => view('tenant.teachers.table', ['teachers' => Teacher::with('user')->get()])->render()
            ]);
        }

        return redirect()->route('tenant.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        if (request()->ajax()) {
            return response()->json($teacher);
        }
        return view('tenant.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$teacher->user_id,
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $teacher->update([
                'qualification' => $request->qualification,
                'specialization' => $request->specialization,
                'joining_date' => $request->joining_date,
                'status' => $request->status,
            ]);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'html' => view('tenant.teachers.table', ['teachers' => Teacher::with('user')->get()])->render()
            ]);
        }

        return redirect()->route('tenant.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $teacher->user->delete();
            $teacher->delete();
        });

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully',
                'html' => view('tenant.teachers.table', ['teachers' => Teacher::with('user')->get()])->render()
            ]);
        }

        return redirect()->route('tenant.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
