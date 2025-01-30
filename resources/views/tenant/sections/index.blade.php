@extends('layouts.master')

@section('title', 'Sections')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Sections</h3>
            <a href="{{ route('tenant.sections.create') }}" class="btn btn-primary">Add New Section</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sections as $section)
                    <tr>
                        <td>{{ $section->name }}</td>
                        <td>{{ $section->classRoom->name }}</td>
                        <td>{{ $section->capacity }}</td>
                        <td>
                            <span class="badge badge-{{ $section->status ? 'success' : 'danger' }}">
                                {{ $section->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tenant.sections.edit', $section) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('tenant.sections.destroy', $section) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
