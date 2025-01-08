@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Teachers</h3>
            <a href="{{ route('tenant.teachers.create') }}" class="btn btn-primary">Add New Teacher</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Qualification</th>
                        <th>Specialization</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                    <tr>
                        <td>{{ $teacher->user->name }}</td>
                        <td>{{ $teacher->user->email }}</td>
                        <td>{{ $teacher->qualification }}</td>
                        <td>{{ $teacher->specialization }}</td>
                        <td>{{ $teacher->joining_date->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge badge-{{ $teacher->status ? 'success' : 'danger' }}">
                                {{ $teacher->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tenant.teachers.edit', $teacher) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('tenant.teachers.destroy', $teacher) }}" method="POST" class="d-inline">
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
