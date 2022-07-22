<div class="table-responsive" id="users-table-wrapper">
    <table class="table table-borderless table-striped">
        <thead>
            <tr>
                <th>No</th>
                    @foreach($table_thead[0] as $value)
                        <th>{{ getTableHead($value[0]) ?? '' }}</th> 
                    @endforeach
                <th >@lang('Created At')</th> 
                <th >@lang('Updated At')</th>   
                <th class="text-center">@lang('Action')</th>
            </tr>
        </thead>

        <tbody>
            @if(count($data) > 0)
               
                
                @foreach ($data as $item)

                    <tr>
                        <td class="align-middle">{{ $loop->index + 1}}</td>

                            @foreach ($item as $key => $value)

                                @if($key == "id" || $key == "project_id" || $key == "user_id")
                                    @continue
                                @endif

                                <td>{{$value}}</td> 
                        

                            @endforeach


                        <td class="text-center align-middle">
                            --
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