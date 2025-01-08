@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Teacher</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('tenant.teachers.update', $teacher) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $teacher->user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $teacher->user->email }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Qualification</label>
                    <input type="text" name="qualification" class="form-control" value="{{ $teacher->qualification }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Specialization</label>
                    <input type="text" name="specialization" class="form-control" value="{{ $teacher->specialization }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control" value="{{ $teacher->joining_date->format('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1" {{ $teacher->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$teacher->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Teacher</button>
            </form>
        </div>
    </div>
</div>
@endsection
