@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Organizations</h3>
                <a href="{{ route('organization.create') }}" class="btn btn-primary">Add New Organization</a>
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
                    @foreach($tenants as $tenant)
                        <tr>
                            <td>{{ @$tenant->name }}</td>
                            <td>{{ @$tenant->domain }}</td>
                            <td>{{ @$tenant->admin_email }}</td>
                            <td>
                            <span class="badge badge-{{ @$tenant->status ? 'success' : 'danger' }}">
                                {{ @$tenant->status ? 'Active' : 'Inactive' }}
                            </span>
                            </td>
                            <td>
                                <a href="{{ route('schools.edit', @$tenant) }}" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
