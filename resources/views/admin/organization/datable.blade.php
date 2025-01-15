@if(!empty($tenants))
    @foreach($tenants as $tenant)
        <tr>
            <td>{{ @$tenant->org_name }}</td>
            <td>{{ @$tenant->domain }}</td>
            <td>{{ @$tenant->email }}</td>
            <td>
                <span class="badge badge-{{ @$tenant->status ? 'success' : 'danger' }}">
                    {{ @$tenant->status ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <a href="javascript:;" class="btn btn-sm btn-primary edit-btn" data-id="{{ @$tenant->id }}">Edit</a>
                <a href="javascript:;" class="btn btn-sm btn-danger delete-btn" data-id="{{ @$tenant->id }}">Delete</a>
            </td>
        </tr>
    @endforeach
@endif
