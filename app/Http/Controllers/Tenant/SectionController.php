<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Section;
use App\Models\Tenant\ClassRoom;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with('classRoom')->get();
        return view('tenant.sections.index', compact('sections'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('tenant.sections.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_room_id' => 'required|exists:class_rooms,id',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        Section::create($validated);

        return redirect()->route('tenant.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $classes = ClassRoom::all();
        return view('tenant.sections.edit', compact('section', 'classes'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_room_id' => 'required|exists:class_rooms,id',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        $section->update($validated);

        return redirect()->route('tenant.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('tenant.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}
