@extends('layouts.app')

@section('page-title', __('Project'))
@section('page-heading', __('Project'))

@section('breadcrumbs')
    <li class="breadcrumb-item active">
        @lang('Project')
    </li>
@stop 

@section('content')

@include('partials.messages')

<div class="card">
    <div class="card-body">

        <form action="" method="GET" id="users-form" class="pb-2 mb-3 border-bottom-light">
            <div class="row my-3 flex-md-row flex-column-reverse">
                <div class="col-md-6 mt-md-0 mt-2">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control input-solid" name="search" value="{{ Request::get('search') }}" placeholder="@lang('Search for project...')">

                            <span class="input-group-append">
                                @if (Request::has('search') && Request::get('search') != '')
                                    <a href="{{ route('project.index') }}" class="btn btn-light d-flex align-items-center text-muted" role="button"> <i class="fas fa-times"></i> </a>
                                @endif
                                <button class="btn btn-light" type="submit" id="search-users-btn"> <i class="fas fa-search text-muted"></i> </button>
                            </span>
                    </div>
                </div>

                @permission('project.create')

                    <div class="col-md-6">
                        <a href="{{ route('project.create') }}" class="btn btn-primary btn-rounded float-right"> <i class="fas fa-plus mr-2"></i> @lang('Project') </a>
                    </div>

                @endpermission

            </div>
        </form>

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
                                    <td>{{$loop->index + 1}}</td>
                                    <td >{{ $item->name}}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td class="text-center">

                                        <a href="{{ route('project.feature.show', $item->id) }}" class="btn btn-icon edit" title="@lang('Project Feature')" data-toggle="tooltip" data-placement="top"> <i class="fas fa-list"></i> </a> 

                                        @permission('project.edit')
                                            <a href="{{ route('project.edit', $item->id) }}" class="btn btn-icon edit" title="@lang('Edit Project')" data-toggle="tooltip" data-placement="top"> <i class="fas fa-edit"></i> </a>
                                        @endpermission
                                        
                                        @permission('project.destroy')
                                            <a href="{{ route('project.destroy', $item->id) }}" class="btn btn-icon" title="@lang('Delete Project')" data-toggle="tooltip" data-placement="top" data-method="DELETE" data-confirm-title="@lang('Please Confirm')" data-confirm-text="@lang('Are you sure that you want to delete this project?')" data-confirm-delete="@lang('Yes, delete it!')"> <i class="fas fa-trash"></i> </a>
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

<nav aria-label="Page navigation example">
    <ul class="pagination"> 
        <?php $page = $paginate->current_page; ?>
        @foreach ($paginate->links as $item)
            <?php 
                $active = $item->label == $page ? 'active' : '';
            ?> 
            <li class="page-item {{$active}}"><a class="page-link" href="{{ $item->url }}"><?php echo $item->label; ?></a></li>
        @endforeach 
    </ul>
</nav>

@stop

@section('scripts')
    <script>
        $("#status").change(function () {
            $("#users-form").submit();
        });
    </script>
@stop
