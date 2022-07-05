<div class="table-responsive" id="users-table-wrapper">
    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th >@lang('Feature')</th>
                <!-- <th class="text-center">@lang('Action')</th> -->
            </tr>
        </thead>
        <tbody>
            @if(count($feature_list))
                @foreach($feature_list as $item)
                    <tr>
                        <td>{{ 1 + $loop->index }}</td>
                        <td>{{ $item }}</td>
                        <!-- <td class="text-center">
                            @permission('project.edit')
                                <a href="{{ route('project.edit', '') }}" class="btn btn-icon edit" title="@lang('Edit Project')" data-toggle="tooltip" data-placement="top"> <i class="fas fa-edit"></i> </a>
                            @endpermission
                            @permission('project.destroy')
                                <a href="{{ route('project.destroy', '') }}" class="btn btn-icon" title="@lang('Delete Project')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this project?')" data-confirm-delete="@lang('Yes, delete it!')"> <i class="fas fa-trash"></i> </a>
                            @endpermission
                        </td> -->
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