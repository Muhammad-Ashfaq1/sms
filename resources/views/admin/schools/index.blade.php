@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Schools</h3>
            <a href="{{ route('schools.create') }}" class="btn btn-primary">Add New School</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Domain</th>
                        <th>Admin Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $school)
                    <tr>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->domain }}</td>
                        <td>{{ $school->admin_email }}</td>
                        <td>
                            <span class="badge badge-{{ $school->status ? 'success' : 'danger' }}">
                                {{ $school->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('schools.edit', $school) }}" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
