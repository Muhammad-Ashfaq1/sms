<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Roll Number</th>
            <th>Parent Name</th>
            <th>Parent Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->user->name }}</td>
            <td>{{ $student->user->email }}</td>
            <td>{{ $student->roll_number }}</td>
            <td>{{ $student->parent_name }}</td>
            <td>{{ $student->parent_phone }}</td>
            <td>
                <span class="badge badge-{{ $student->status ? 'success' : 'danger' }}">
                    {{ $student->status ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <a href="javascript:;" class="btn btn-sm btn-primary edit-btn" data-id="{{ $student->id }}">Edit</a>
                <form action="{{ route('tenant.students.destroy', $student) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
