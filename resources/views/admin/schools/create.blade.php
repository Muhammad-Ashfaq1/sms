@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New School</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('schools.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">School Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Domain</label>
                    <input type="text" name="domain" class="form-control" required>
                    <small class="text-muted">e.g., school1.example.com</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input type="email" name="admin_email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Create School</button>
            </form>
        </div>
    </div>
</div>
@endsection
