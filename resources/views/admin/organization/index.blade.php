@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Organizations</h3>
                <a href="javascript:;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrganizationModal">Add New Organization</a>
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
                    <tbody id="js-organization-table">
                    @include('admin.organization.datable')
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal for Add/Edit Organization --}}
    <div class="modal fade" id="createOrganizationModal" tabindex="-1" aria-labelledby="createOrganizationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createOrganizationModalLabel">Create New Organization</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="organization-form" method="POST">
                        @csrf
                        <input type="hidden" name="tenant_id" id="tenant_id">
                        <!-- Organization Name -->
                        <div class="mb-3">
                            <label for="org_name" class="form-label">Organization Name</label>
                            <input type="text" id="org_name" name="org_name" class="form-control" required>
                        </div>
                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>
                        <!-- Domain -->
                        <div class="mb-3">
                            <label for="domain" class="form-label">Domain</label>
                            <input type="text" id="js-domain" name="domain" class="form-control" required>
                        </div>
                        <!-- Admin Email -->
                        <div class="mb-3">
                            <label for="admin_email" class="form-label">Admin Email</label>
                            <input type="email" id="admin_email" name="email" class="form-control" required>
                        </div>
                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary" id="submit-btn">Create Organization</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function() {
            // Open modal to create new organization
            $('#createOrganizationModal').on('show.bs.modal', function (e) {
                var modal = $(this);
                modal.find('.modal-title').text('Create New Organization');
                modal.find('form')[0].reset();
                $('#tenant_id').val('');
                $('#submit-btn').text('Create Organization');
                $('#js-domain').prop('readonly', false);
            });

            // Submit the form using AJAX (Create or Update)
            $('#organization-form').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                var actionUrl = $('#tenant_id').val() ? "{{ route('organization.update', ':id') }}".replace(':id', $('#tenant_id').val()) : "{{ route('organization.store') }}";

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#createOrganizationModal').modal('hide');
                        $('#js-organization-table').html();
                        $('#js-organization-table').html(response.html);
                        Swal.fire('Success!', 'Organization saved successfully.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });

            // Open modal to edit organization
            $(document).on('click', '.edit-btn', function() {
                var tenantId = $(this).data('id');
                $.get("{{ route('organization.edit', '') }}/" + tenantId, function(data) {
                    $('#createOrganizationModal').modal('show');
                    $('#tenant_id').val(data.id);
                    $('#org_name').val(data.org_name);
                    $('#address').val(data.address);
                    $('#js-domain').val(data.domains[0].domain); // Set the domain dynamically
                    $('#admin_email').val(data.email);
                    $('#status').val(data.status);
                    $('#submit-btn').text('Update Organization');
                    $('#createOrganizationModalLabel').text('Edit Organization');
                    $('#domain').prop('readonly', false); // Allow editing domain if it's editable
                });
            });

            // SweetAlert Confirmation before Delete
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();

                var tenantId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('organization.destroy', ':id') }}".replace(':id', tenantId),
                            method: 'DELETE',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            },
                            success: function(response) {
                                $('#js-organization-table').html();
                                $('#js-organization-table').html(response.html);
                                Swal.fire('Deleted!', 'Organization has been deleted.', 'success');
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
