@extends('layouts.master')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Teachers</h3>
            <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teacherModal">Add New Teacher</a>
        </div>
        <div class="card-body">
            <div id="js-teacher-table">
                @include('tenant.teachers.table')
            </div>
        </div>
    </div>
</div>

{{-- Modal for Add/Edit Teacher --}}
<div class="modal fade" id="teacherModal" tabindex="-1" aria-labelledby="teacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacherModalLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="teacher-form" method="POST">
                    @csrf
                    <input type="hidden" name="teacher_id" id="teacher_id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="teacher_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="teacher_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Qualification</label>
                        <input type="text" name="qualification" id="teacher_qualification" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" id="teacher_specialization" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joining_date" id="teacher_joining_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="teacher_status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Create Teacher</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    $(document).ready(function() {
        // Helper function to format date
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.getFullYear().toString() + '-' +
                   String(date.getMonth() + 1).padStart(2, '0') + '-' +
                   String(date.getDate()).padStart(2, '0');
        }

        // Open modal to create new teacher
        $('#teacherModal').on('show.bs.modal', function (e) {
            var modal = $(this);
            modal.find('.modal-title').text('Add New Teacher');
            modal.find('form')[0].reset();
            $('#teacher_id').val('');
            $('#submit-btn').text('Create Teacher');

            // Set today's date as default for new records
            var today = new Date().toISOString().split('T')[0];
            $('#teacher_joining_date').val(today);
        });

        // Open modal to edit teacher
        $(document).on('click', '.edit-btn', function() {
            var teacherId = $(this).data('id');
            var editUrl = "{{ route('tenant.teachers.edit', ':id') }}".replace(':id', teacherId);

            $.get(editUrl, function(data) {
                console.log('Teacher Data:', data); // Debug log

                $('#teacherModal').modal('show');
                $('#teacher_id').val(data.id);
                $('#teacher_name').val(data.user.name);
                $('#teacher_email').val(data.user.email);
                $('#teacher_qualification').val(data.qualification);
                $('#teacher_specialization').val(data.specialization);

                // Format and set the joining date
                $('#teacher_joining_date').val(formatDate(data.joining_date));

                // Set the status - convert to string to match option values
                $('#teacher_status').val(String(data.status));

                $('#submit-btn').text('Update Teacher');
                $('#teacherModalLabel').text('Edit Teacher');
            }).fail(function(xhr) {
                console.error('Error fetching teacher:', xhr); // Debug log
            });
        });

        // Submit form
        $('#teacher-form').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var teacherId = $('#teacher_id').val();
            var actionUrl = teacherId ?
                "{{ route('tenant.teachers.update', ':id') }}".replace(':id', teacherId) :
                "{{ route('tenant.teachers.store') }}";

            $.ajax({
                url: actionUrl,
                method: teacherId ? 'PUT' : 'POST',
                data: formData + (teacherId ? '&_method=PUT' : ''),
                success: function(response) {
                    $('#teacherModal').modal('hide');
                    if (response.html) {
                        $('#js-teacher-table').html(response.html);
                    } else {
                        location.reload();
                    }
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    // Show error message with details if available
                    var errorMessage = 'Something went wrong. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>
@endsection
