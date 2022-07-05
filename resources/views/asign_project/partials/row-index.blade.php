<div class="table-responsive" id="users-table-wrapper">
    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th >Username</th>
                <th >Role</th>
                <th >Joined Projects</th> 
                <th class="text-center">@lang('Action')</th>
            </tr>
        </thead>
        <tbody> 
            @if(count($asigns) > 0)
                @foreach($asigns as $item)
                    @if($item->id == auth()->user()->id)
                        @continue
                    @endif
                        <tr>
                            <td class="align-middle">{{ $loop->index + 1 }}</td>
                            <td class="align-middle">{{ $item->username }}</td>
                            <td class="align-middle">{{ $item->role->display_name }}</td>
                            <td class="align-middle"> {{ $item->role_id == 2 ? getDisplayProjects($item->children_asignproject) : getDisplayFolders($item->children_asignfolder) }} </td> 
                            
                            <td class="text-center align-middle"> 
                                <a href="{{ route('asignproject.show', $item->id) }}" class="btn btn-icon {{ checkNoAsignProject($item->role_id) }}" style="text-decoration: none;"><span class="badge badge-primary p-2">Project</span> </a> 
                                <a href="{{ route('asignuser.show', $item->id) }}" class="btn btn-icon {{ checkIsSuperAdmin($item->role_id, $item->parent_id) }}"  data-placement="top"><span class="badge badge-secondary p-2">Monitor</span> </a> 
                                <a href="{{ route('asignuser.destroy', $item->id) }}" class="btn btn-icon {{ checkCanRemoveAsign($item->role_id) }}" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to remove this asign?')" data-confirm-delete="@lang('Yes, remove asign!')"><span class="badge badge-danger p-2">Remove</span></a>
                            </td> 
                        </tr>
                @endforeach 
            @else
                <tr>
                    <td colspan="5">
                        No record
                    </td>
                </tr>
            @endif  
        </tbody>
    </table>
</div>