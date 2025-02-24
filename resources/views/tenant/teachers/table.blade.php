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
                <a href="javascript:;" class="btn btn-sm btn-primary edit-btn" data-id="{{ $teacher->id }}">Edit</a>
                <a href="javascript:;" class="btn btn-sm btn-danger delete-btn"
                   onclick="confirmDelete('{{ route('tenant.teachers.destroy', $teacher->id) }}', '{{ csrf_token() }}')">
                    Delete
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
