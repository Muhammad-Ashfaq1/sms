<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ClassRoom;
use App\Models\Tenant\Teacher;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::with('teacher.user')->get();

        return view('tenant.classes.index', compact('classes'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();

        return view('tenant.classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        ClassRoom::create($request->validated());

        return redirect()->route('tenant.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(ClassRoom $class)
    {
        $teachers = Teacher::with('user')->get();

        return view('tenant.classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $class->update($request->validated());

        return redirect()->route('tenant.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();

        return redirect()->route('tenant.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
