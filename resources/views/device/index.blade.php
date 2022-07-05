@extends('layouts.app')
@section('page-title', __('Devices'))
@section('page-heading', __('Devices'))

@section('breadcrumbs')
    <li class="breadcrumb-item"> 
        <a href="">Project</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Devices')
    </li>
@stop
@section('content')
@include('partials.messages')

<div class="card">
    <div class="card-body">

        <div class="pb-2 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">

                <div class="col-md-4 mt-md-0 mt-2">
                    <form action="" method="GET" id="users-form" >
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control input-solid" name="search" value="{{ Request::get('search') }}" placeholder="@lang('Search for devices...')">

                            <span class="input-group-append"> 
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('project.device.show', $folder_id .','. $pro_id) }}" class="btn btn-light d-flex align-items-center text-muted" role="button"><i class="fas fa-times"></i></a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div> 

                <div class="col-md-2 mt-2 mt-md-0">
                    <form action="" method="GET"  accept-charset="UTF-8">
                        <input type="hidden" class="form-control" name="_token" value="{{ csrf_token() }}"> 
                        <select  class="form-control" onchange="this.form.submit()" name="project" required >                
                            @foreach($list_feature as $f)
                                <option value="{{ $f }}" {{ $f==$device ? 'selected' : '' }} >{{ $f }}</option>
                            @endforeach
                        
                            {{ csrf_field() }}           
                        </select>                 
                    </form>
                </div>

                <div class="col-md-6 float-right">  
                    <a href="{{ route('project.device.create', $folder_id.','.$device) }}" class="btn btn-primary btn-rounded float-right"> <i class="fas fa-plus"></i> @lang('Device')</a>
                    @permission('project.category')
                        <a href="" class="btn btn-primary btn-rounded mr-2 float-right"> <i class="fas fa-plus"></i>@lang('Category')</a>
                    @endpermission
                </div>

            </div>
        </div>
        
        <div class="table-responsive" id="users-table-wrapper">
            <table class="table table-borderless table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th >@lang('Device')</th>
                        <th class="text-center">@lang('Action')</th>
                    </tr>
                </thead> 
                <tbody> 

                    @if(count($paginate) > 0)
                        @foreach($paginate as $item)
                            <tr>
                                <td>{{ 1 + $loop->index}}</td>
                                <td>{{ $item->Name ?? "" }}</td>
                                <td class="text-center">
                                    <a href="{{ route('project.device.edit', $item->id.','.$table_name) }}" class="btn btn-icon edit" title="@lang('Edit Project')" data-toggle="tooltip" data-placement="top"> <i class="fas fa-edit"></i> </a>
                                    <a href="{{ route('project.device.destroy', $item->id . ',' . $pro_id . ',' .$table_name) }}" class="btn btn-icon" title="@lang('Delete Project')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this project?')" data-confirm-delete="@lang('Yes, delete it!')"> <i class="fas fa-trash"></i> </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3"><em>@lang('No Record.')</em></td>
                        </tr>
                    @endif
   
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section('scripts')
    <script>
        $("#song").change(function () {
            $("#feature-form").submit();
        });
    </script>
@stop

