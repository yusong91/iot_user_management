@extends('layouts.app')
@section('page-title', __('Devices'))
@section('page-heading', __('Devices'))

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('device.show', $project->id) }}">Project</a>
    </li>
    <li class="breadcrumb-item active">
        @lang('Devices')
    </li>
@stop
@section('content')
@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-4 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text"
                               class="form-control input-solid"
                               name="search"
                               value="{{ Request::get('search') }}"
                               placeholder="@lang('Search for devices...')">

                            <span class="input-group-append"> 
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('device.show', $project->id) }}"
                                           class="btn btn-light d-flex align-items-center text-muted"
                                           role="button">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn">
                                    <i class="fas fa-search text-muted"></i>
                                </button>
                            </span>
                    </div>
                </div>

                <div class="col-md-2 mt-2 mt-md-0">

                    {!!
                        Form::select('category', $device_list, Request::get('category'), ['id' => 'category', 'class' => 'form-control input-solid'])
                    !!}

                </div>

                @permission('device.create')

                <div class="col-md-6 float-right">  
                    <div class="row"> 

                        <div class="col-12">

                            <a href="{{ route('device.create', $category.','.$project->id) }}" class="btn btn-primary btn-rounded float-right">
                                <i class="fas fa-plus"></i>
                                @lang('Device')
                            </a>

                            <a href="{{ route('folder.create') }}" class="btn btn-primary btn-rounded float-right mr-2">
                                <i class="fas fa-plus"></i>
                                @lang('Folder')
                            </a>
                            @permission('project.category')
                            <a href="{{ route('category.create', $project->id) }}" class="btn btn-primary btn-rounded mr-2 float-right">
                                <i class="fas fa-plus"></i>
                                @lang('Category')
                            </a>
                            @endpermission
                        </div>
                    </div>
                </div>

                @endpermission

            </div>
        </form>

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
                        @if(count($devices))

                            @foreach($devices as $item)
                                <tr>
                                    <td>{{1 + $loop->index }}</td>
                                    <td>{{ getValueWithKey($item) }}</td>
                                    <td class="text-center">

                                        @permission('device.edit')
                                            <a href="{{ route('device.edit', $category.','.$item->id) }}" class="btn btn-icon edit" title="@lang('Edit Device')"data-toggle="tooltip" data-placement="top">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endpermission
                                        
                                        @permission('device.destroy')
                                            <a href="{{ route('device.destroy', $item->project_id.','.$category.','. $item->id) }}" class="btn btn-icon" title="@lang('Delete Device')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this device?')" data-confirm-delete="@lang('Yes, delete it!')"> <i class="fas fa-trash"></i></a>
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
    </div>
</div>

@stop

@section('scripts')
    <script>
        $("#category").change(function () {
            $("#users-form").submit();
        });
    </script>
@stop

