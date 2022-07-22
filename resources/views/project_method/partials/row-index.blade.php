<div class="table-responsive mt-3" id="users-table-wrapper">
    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>No</th>
                    @foreach($table_thead[0] as $value)
                        <th>{{ getTableHead($value[0]) ?? '' }}</th> 
                    @endforeach
                <th class="text-center">@lang('Action')</th>
            </tr>
        </thead>

        <tbody>
            @if(count($data) > 0)
               
                @foreach ($data as $item)

                    <tr>
                        <td class="align-middle">{{ $loop->index + 1}}</td>

                            @foreach ($item as $key => $value)

                                @if($key == "id" || $key == "project_id" || $key == "user_id" || $key == "created_at" || $key == "updated_at")
                                    @continue
                                @endif

                                <td>{{$value}}</td> 
                        
                            @endforeach

                        <td class="text-center align-middle">
                        
                            <a href="" class="btn btn-icon edit" title="@lang('Edit Method')" data-toggle="tooltip" data-placement="top"> <i class="fas fa-edit"></i> {{ $item->id }}</a>
                                       
                            <a href="" class="btn btn-icon" title="@lang('Delete Method')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this method?')" data-confirm-delete="@lang('Yes, delete it!')"> <i class="fas fa-trash"></i> </a>
                                        
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