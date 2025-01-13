@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isset($tenant) ? 'Edit Organization' : 'Create New Organization' }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ isset($tenant) ? route('organization.update', $tenant->id) : route('organization.store') }}" method="POST">
                    @csrf
                    @if(isset($tenant))
                        @method('PUT')
                    @endif

                    <input type="hidden" name="tenant_id" value="{{ $tenant->id ?? '' }}">

                    <!-- Organization Name -->
                    <div class="mb-3">
                        <label for="org_name" class="form-label">Organization Name</label>
                        <input type="text" id="org_name" name="org_name"
                               class="form-control @error('org_name') is-invalid @enderror"
                               value="{{ old('org_name', $tenant->org_name ?? '') }}" required>
                        @error('org_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $tenant->address ?? '') }}" required>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Domain -->
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="text" id="domain" name="domain"
                               class="form-control @error('domain') is-invalid @enderror"
                               value="{{ old('domain', $tenant->domain ?? '') }}" required>
                        <small class="text-muted">e.g., school1.example.com</small>
                        @error('domain')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Admin Email -->
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Admin Email</label>
                        <input type="email" id="admin_email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $tenant->email ?? '') }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status"
                                class="form-control @error('status') is-invalid @enderror" required>
                            <option value="1" {{ old('status', $tenant->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $tenant->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        {{ isset($tenant) ? 'Update Organization' : 'Create Organization' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
