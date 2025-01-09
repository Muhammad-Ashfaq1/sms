@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark">Teachers</h3>
            </div>
            <div class="card-body pt-2">
                <div class="d-flex align-items-center mb-8">
                    <span class="bullet bullet-vertical h-40px bg-success"></span>
                    <div class="flex-grow-1 ms-4">
                        <a href="{{ route('tenant.teachers.index') }}" class="text-gray-800 text-hover-primary fw-bolder fs-6">Total Teachers</a>
                        <span class="text-muted fw-bold d-block">{{ \App\Models\Tenant\Teacher::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark">Students</h3>
            </div>
            <div class="card-body pt-2">
                <div class="d-flex align-items-center mb-8">
                    <span class="bullet bullet-vertical h-40px bg-primary"></span>
                    <div class="flex-grow-1 ms-4">
                        <a href="{{ route('tenant.students.index') }}" class="text-gray-800 text-hover-primary fw-bolder fs-6">Total Students</a>
                        <span class="text-muted fw-bold d-block">{{ \App\Models\Tenant\Student::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card card-xl-stretch mb-xl-8">
            <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark">Classes</h3>
            </div>
            <div class="card-body pt-2">
                <div class="d-flex align-items-center mb-8">
                    <span class="bullet bullet-vertical h-40px bg-warning"></span>
                    <div class="flex-grow-1 ms-4">
                        <a href="{{ route('tenant.classes.index') }}" class="text-gray-800 text-hover-primary fw-bolder fs-6">Total Classes</a>
                        <span class="text-muted fw-bold d-block">{{ \App\Models\Tenant\ClassRoom::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
