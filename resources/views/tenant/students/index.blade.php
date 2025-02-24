@extends('layouts.master')

@section('title', 'Students')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Students</h3>
            <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal">Add New Student</a>
        </div>
        <div class="card-body">
            <div id="js-student-table">
                @include('tenant.students.table')
            </div>
        </div>
    </div>
</div>

{{-- Modal for Add/Edit Student --}}
<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="student-form" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" id="student_id">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="student_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="student_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Roll Number</label>
                        <input type="text" name="roll_number" id="student_roll_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="student_date_of_birth" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Name</label>
                        <input type="text" name="parent_name" id="student_parent_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Phone</label>
                        <input type="text" name="parent_phone" id="student_parent_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" id="student_address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="student_status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Create Student</button>
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

        // Open modal to create new student
        $('#studentModal').on('show.bs.modal', function (e) {
            var modal = $(this);
            modal.find('.modal-title').text('Add New Student');
            modal.find('form')[0].reset();
            $('#student_id').val('');
            $('#submit-btn').text('Create Student');

            // Set today's date as default for date of birth
            var today = new Date().toISOString().split('T')[0];
            $('#student_date_of_birth').val(today);
        });

        // Open modal to edit student
        $(document).on('click', '.edit-btn', function() {
            var studentId = $(this).data('id');
            var editUrl = "{{ route('tenant.students.edit', ':id') }}".replace(':id', studentId);

            $.get(editUrl, function(data) {
                console.log('Student Data:', data); // Debug log

                $('#studentModal').modal('show');
                $('#student_id').val(data.id);
                $('#student_name').val(data.user.name);
                $('#student_email').val(data.user.email);
                $('#student_roll_number').val(data.roll_number);
                $('#student_date_of_birth').val(formatDate(data.date_of_birth));
                $('#student_parent_name').val(data.parent_name);
                $('#student_parent_phone').val(data.parent_phone);
                $('#student_address').val(data.address);
                $('#student_status').val(String(data.status));

                $('#submit-btn').text('Update Student');
                $('#studentModalLabel').text('Edit Student');
            }).fail(function(xhr) {
                console.error('Error fetching student:', xhr);
            });
        });

        // Submit form
        $('#student-form').on('submit', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();
            var studentId = $('#student_id').val();
            var actionUrl = studentId ?
                "{{ route('tenant.students.update', ':id') }}".replace(':id', studentId) :
                "{{ route('tenant.students.store') }}";

            $.ajax({
                url: actionUrl,
                method: studentId ? 'PUT' : 'POST',
                data: formData + (studentId ? '&_method=PUT' : ''),
                success: function(response) {
                    $('#studentModal').modal('hide');
                    if (response.html) {
                        $('#js-student-table').html(response.html);
                    } else {
                        location.reload();
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
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
