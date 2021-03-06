<div class="table-responsive" id="users-table-wrapper">
    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th >@lang('Project')</th>
                <th >@lang('Created_at')</th>
                <th class="text-center">@lang('Action')</th>
            </tr>
        </thead>
        <tbody>
            @if(count($projects))
                @foreach($projects as $item)  
                    <tr>
                        <td class="align-middle">{{$loop->index + 1}}</td>
                        <td class="align-middle">{{ $item->name}}</td>
                        <td class="align-middle">{{ $item->created_at }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('project.device.show', $item->id.','.$item->project_id) }}" class="btn btn-icon edit" data-placement="top"> <span class="badge badge-primary p-2">Device</span> </a> 
                            @permission('project.edit')
                                <a href="{{ route('project.edit', $item->id) }}" class="btn btn-icon edit" title="@lang('Edit Project')" data-toggle="tooltip" data-placement="top"> <i class="fas fa-edit"></i> </a>
                            @endpermission
                            @permission('admin.project.destroy')
                                <a href="{{ route('project.folder.destroy', $item->id) }}" class="btn btn-icon" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this project?')" data-confirm-delete="@lang('Yes, delete it!')"> <span class="badge badge-danger p-2">Delete</span> </a>
                            @endpermission
                        </td> 
                    </tr>
                @endforeach
            @else 
                <tr>
                    <td colspan="7"><em>@lang('No records found.')</em></td>
                </tr>
            @endif

        </tbody>
    </table>
</div>